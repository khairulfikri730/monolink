"use client";

import { useState, useCallback } from "react";
import { toast } from "sonner";
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  type DragEndEvent,
} from "@dnd-kit/core";
import {
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
  arrayMove,
} from "@dnd-kit/sortable";
import { useSortable } from "@dnd-kit/sortable";
import { CSS } from "@dnd-kit/utilities";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Switch } from "@/components/ui/switch";
import { Badge } from "@/components/ui/badge";
import { LinkFormModal } from "./LinkFormModal";
import {
  GripVertical,
  Plus,
  Pencil,
  Trash2,
  Link2,
  Loader2,
} from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";

export interface LinkItem {
  id: string;
  title: string;
  url: string;
  type: string;
  icon: string | null;
  description: string | null;
  isActive: boolean;
  sortOrder: number;
}

interface LinksManagerProps {
  initialLinks: LinkItem[];
}

function SortableLinkRow({
  link,
  onEdit,
  onDelete,
  onToggle,
}: {
  link: LinkItem;
  onEdit: (link: LinkItem) => void;
  onDelete: (id: string) => void;
  onToggle: (id: string, isActive: boolean) => void;
}) {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } =
    useSortable({ id: link.id });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
  };

  return (
    <div
      ref={setNodeRef}
      style={style}
      className="flex items-center gap-3 px-4 py-3 border-b border-border last:border-0 bg-white hover:bg-neutral-50/50 transition-colors group"
    >
      {/* Drag handle */}
      <button
        {...attributes}
        {...listeners}
        className="text-neutral-300 hover:text-neutral-500 cursor-grab active:cursor-grabbing touch-none flex-shrink-0"
        aria-label="Drag to reorder"
      >
        <GripVertical className="w-4 h-4" />
      </button>

      {/* Icon */}
      <div className="w-9 h-9 rounded-lg bg-neutral-100 flex items-center justify-center flex-shrink-0">
        <Link2 className="w-4 h-4 text-neutral-400" />
      </div>

      {/* Info */}
      <div className="flex-1 min-w-0">
        <p className="text-sm font-medium text-neutral-800 truncate">{link.title}</p>
        <p className="text-xs text-neutral-400 truncate">{link.url}</p>
      </div>

      {/* Type badge */}
      <Badge className="hidden sm:inline-flex bg-neutral-100 text-neutral-500 border-neutral-200 text-xs capitalize flex-shrink-0">
        {link.type.replace("_", " ").toLowerCase()}
      </Badge>

      {/* Active toggle */}
      <Switch
        checked={link.isActive}
        onCheckedChange={(checked) => onToggle(link.id, checked)}
        aria-label={`${link.title} active`}
      />

      {/* Actions */}
      <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
        <Button
          id={`edit-link-${link.id}`}
          variant="ghost"
          size="sm"
          className="w-8 h-8 p-0"
          onClick={() => onEdit(link)}
        >
          <Pencil className="w-3.5 h-3.5" />
        </Button>
        <Button
          id={`delete-link-${link.id}`}
          variant="ghost"
          size="sm"
          className="w-8 h-8 p-0 text-neutral-400 hover:text-destructive hover:bg-red-50"
          onClick={() => onDelete(link.id)}
        >
          <Trash2 className="w-3.5 h-3.5" />
        </Button>
      </div>
    </div>
  );
}

export function LinksManager({ initialLinks }: LinksManagerProps) {
  const [links, setLinks] = useState<LinkItem[]>(initialLinks);
  const [showAddModal, setShowAddModal] = useState(false);
  const [editLink, setEditLink] = useState<LinkItem | null>(null);
  const [deleteId, setDeleteId] = useState<string | null>(null);
  const [deleting, setDeleting] = useState(false);
  const [reordering, setReordering] = useState(false);

  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const handleDragEnd = useCallback(
    async (event: DragEndEvent) => {
      const { active, over } = event;
      if (!over || active.id === over.id) return;

      const oldIndex = links.findIndex((l) => l.id === active.id);
      const newIndex = links.findIndex((l) => l.id === over.id);
      const reordered = arrayMove(links, oldIndex, newIndex);
      setLinks(reordered);

      setReordering(true);
      try {
        const res = await fetch("/api/links", {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ linkIds: reordered.map((l) => l.id) }),
        });
        if (!res.ok) {
          toast.error("Unable to save order.");
          setLinks(links); // revert
        }
      } catch {
        toast.error("Unable to save order.");
        setLinks(links);
      } finally {
        setReordering(false);
      }
    },
    [links]
  );

  const handleToggle = async (id: string, isActive: boolean) => {
    setLinks((prev) =>
      prev.map((l) => (l.id === id ? { ...l, isActive } : l))
    );
    try {
      const res = await fetch(`/api/links/${id}`, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ isActive }),
      });
      if (!res.ok) {
        setLinks((prev) =>
          prev.map((l) => (l.id === id ? { ...l, isActive: !isActive } : l))
        );
        toast.error("Unable to update link.");
      }
    } catch {
      setLinks((prev) =>
        prev.map((l) => (l.id === id ? { ...l, isActive: !isActive } : l))
      );
      toast.error("Unable to update link.");
    }
  };

  const handleDelete = async () => {
    if (!deleteId) return;
    setDeleting(true);
    try {
      const res = await fetch(`/api/links/${deleteId}`, { method: "DELETE" });
      if (!res.ok) {
        toast.error("Unable to delete link.");
        return;
      }
      setLinks((prev) => prev.filter((l) => l.id !== deleteId));
      toast.success("Link deleted.");
      setDeleteId(null);
    } catch {
      toast.error("Unable to delete link.");
    } finally {
      setDeleting(false);
    }
  };

  const handleSaved = (link: LinkItem, isNew: boolean) => {
    if (isNew) {
      setLinks((prev) => [...prev, link]);
    } else {
      setLinks((prev) => prev.map((l) => (l.id === link.id ? link : l)));
    }
    setShowAddModal(false);
    setEditLink(null);
  };

  return (
    <>
      {/* Add Button */}
      <div className="flex items-center justify-between">
        <p className="text-sm text-neutral-500">
          {links.length} link{links.length !== 1 ? "s" : ""}
          {reordering && (
            <span className="ml-2 text-xs text-brand-500">
              <Loader2 className="w-3 h-3 inline animate-spin mr-1" />
              Saving order…
            </span>
          )}
        </p>
        <Button id="add-link-btn" onClick={() => setShowAddModal(true)}>
          <Plus className="w-4 h-4 mr-2" />
          Add Link
        </Button>
      </div>

      {/* Links List */}
      <Card className="border-border shadow-sm overflow-hidden">
        {links.length === 0 ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3">
            <div className="w-14 h-14 rounded-2xl bg-neutral-100 flex items-center justify-center">
              <Link2 className="w-6 h-6 text-neutral-400" />
            </div>
            <p className="text-neutral-600 font-medium">No links yet</p>
            <p className="text-neutral-400 text-sm text-center">
              Add your first link to appear on your public profile.
            </p>
            <Button id="add-first-link-btn" onClick={() => setShowAddModal(true)} className="mt-2">
              <Plus className="w-4 h-4 mr-2" />
              Add Link
            </Button>
          </div>
        ) : (
          <DndContext
            sensors={sensors}
            collisionDetection={closestCenter}
            onDragEnd={handleDragEnd}
          >
            <SortableContext
              items={links.map((l) => l.id)}
              strategy={verticalListSortingStrategy}
            >
              {links.map((link) => (
                <SortableLinkRow
                  key={link.id}
                  link={link}
                  onEdit={(l) => setEditLink(l)}
                  onDelete={(id) => setDeleteId(id)}
                  onToggle={handleToggle}
                />
              ))}
            </SortableContext>
          </DndContext>
        )}
      </Card>

      {/* Add Link Modal */}
      {showAddModal && (
        <LinkFormModal
          onClose={() => setShowAddModal(false)}
          onSaved={(link) => handleSaved(link, true)}
        />
      )}

      {/* Edit Link Modal */}
      {editLink && (
        <LinkFormModal
          link={editLink}
          onClose={() => setEditLink(null)}
          onSaved={(link) => handleSaved(link, false)}
        />
      )}

      {/* Delete Confirmation */}
      <Dialog open={!!deleteId} onOpenChange={() => setDeleteId(null)}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Delete link?</DialogTitle>
            <DialogDescription>
              This link will be permanently removed from your profile.
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="outline" onClick={() => setDeleteId(null)} disabled={deleting}>
              Cancel
            </Button>
            <Button variant="destructive" onClick={handleDelete} disabled={deleting}>
              {deleting ? <><Loader2 className="w-4 h-4 mr-2 animate-spin" />Deleting…</> : "Delete"}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
