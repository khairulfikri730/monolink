"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import {
  MoreHorizontal,
  Pencil,
  UserCheck,
  UserX,
  Trash2,
  KeyRound,
  Loader2,
} from "lucide-react";

interface UserActionsDropdownProps {
  userId: string;
  userName: string;
  currentStatus: "ACTIVE" | "INACTIVE";
}

export function UserActionsDropdown({
  userId,
  userName,
  currentStatus,
}: UserActionsDropdownProps) {
  const router = useRouter();
  const [loading, setLoading] = useState<string | null>(null);
  const [deleteDialog, setDeleteDialog] = useState(false);

  const call = async (
    endpoint: string,
    method: string,
    body?: object
  ) => {
    const res = await fetch(endpoint, {
      method,
      headers: { "Content-Type": "application/json" },
      body: body ? JSON.stringify(body) : undefined,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.error || "Something went wrong.");
    return data;
  };

  const handleToggleStatus = async () => {
    const newStatus = currentStatus === "ACTIVE" ? "INACTIVE" : "ACTIVE";
    setLoading("toggle");
    try {
      await call(`/api/admin/users/${userId}/status`, "PATCH", {
        status: newStatus,
      });
      toast.success(
        newStatus === "ACTIVE"
          ? `${userName} has been enabled.`
          : `${userName} has been disabled.`
      );
      router.refresh();
    } catch (e) {
      toast.error(e instanceof Error ? e.message : "Unable to update status.");
    } finally {
      setLoading(null);
    }
  };

  const handleDelete = async () => {
    setLoading("delete");
    try {
      await call(`/api/admin/users/${userId}`, "DELETE");
      toast.success(`${userName} has been deleted.`);
      setDeleteDialog(false);
      router.refresh();
    } catch (e) {
      toast.error(e instanceof Error ? e.message : "Unable to delete user.");
    } finally {
      setLoading(null);
    }
  };

  return (
    <>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button
            id={`user-actions-${userId}`}
            variant="ghost"
            size="sm"
            className="w-8 h-8 p-0"
          >
            <MoreHorizontal className="w-4 h-4" />
            <span className="sr-only">Actions for {userName}</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" className="w-48">
          <DropdownMenuItem asChild>
            <a href={`/admin/users/${userId}/edit`}>
              <Pencil className="w-4 h-4 mr-2" />
              Edit
            </a>
          </DropdownMenuItem>
          <DropdownMenuItem asChild>
            <a href={`/admin/users/${userId}/reset-password`}>
              <KeyRound className="w-4 h-4 mr-2" />
              Reset Password
            </a>
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem
            onClick={handleToggleStatus}
            disabled={loading === "toggle"}
          >
            {currentStatus === "ACTIVE" ? (
              <>
                <UserX className="w-4 h-4 mr-2 text-neutral-500" />
                Disable
              </>
            ) : (
              <>
                <UserCheck className="w-4 h-4 mr-2 text-green-600" />
                Enable
              </>
            )}
            {loading === "toggle" && (
              <Loader2 className="w-3.5 h-3.5 ml-auto animate-spin" />
            )}
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem
            onClick={() => setDeleteDialog(true)}
            className="text-destructive focus:text-destructive"
          >
            <Trash2 className="w-4 h-4 mr-2" />
            Delete
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      {/* Delete Confirmation */}
      <Dialog open={deleteDialog} onOpenChange={setDeleteDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Delete user?</DialogTitle>
            <DialogDescription>
              This will permanently delete <strong>{userName}</strong> and all
              their data including profile, links, and analytics. This action
              cannot be undone.
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setDeleteDialog(false)}
              disabled={loading === "delete"}
            >
              Cancel
            </Button>
            <Button
              id={`confirm-delete-${userId}`}
              variant="destructive"
              onClick={handleDelete}
              disabled={loading === "delete"}
            >
              {loading === "delete" ? (
                <>
                  <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                  Deleting…
                </>
              ) : (
                "Delete"
              )}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
