"use client";

import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { linkSchema, type LinkSchema } from "@/lib/validations";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Switch } from "@/components/ui/switch";
import { toast } from "sonner";
import { Loader2 } from "lucide-react";
import type { LinkItem } from "./LinksManager";

const LINK_TYPES = [
  { value: "CUSTOM", label: "Custom" },
  { value: "INSTAGRAM", label: "Instagram" },
  { value: "TIKTOK", label: "TikTok" },
  { value: "YOUTUBE", label: "YouTube" },
  { value: "FACEBOOK", label: "Facebook" },
  { value: "LINKEDIN", label: "LinkedIn" },
  { value: "X_TWITTER", label: "X / Twitter" },
  { value: "WHATSAPP", label: "WhatsApp" },
  { value: "EMAIL", label: "Email" },
  { value: "TELEGRAM", label: "Telegram" },
  { value: "PHONE", label: "Phone" },
  { value: "GOOGLE_MAPS", label: "Google Maps" },
  { value: "WEBSITE", label: "Website" },
  { value: "SHOPEE", label: "Shopee" },
  { value: "TOKOPEDIA", label: "Tokopedia" },
];

interface LinkFormModalProps {
  link?: LinkItem;
  onClose: () => void;
  onSaved: (link: LinkItem) => void;
}

export function LinkFormModal({ link, onClose, onSaved }: LinkFormModalProps) {
  const isEdit = !!link;
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors },
  } = useForm<LinkSchema>({
    resolver: zodResolver(linkSchema),
    defaultValues: {
      title: link?.title ?? "",
      url: link?.url ?? "",
      type: link?.type ?? "CUSTOM",
      icon: link?.icon ?? "",
      description: link?.description ?? "",
      isActive: link?.isActive ?? true,
    },
  });

  const isActive = watch("isActive");

  const onSubmit = async (data: LinkSchema) => {
    setLoading(true);
    try {
      const res = await fetch(isEdit ? `/api/links/${link.id}` : "/api/links", {
        method: isEdit ? "PATCH" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const json = await res.json();
      if (!res.ok) {
        toast.error(json.error || "Unable to save link.");
        return;
      }
      toast.success(isEdit ? "Link updated." : "Link added.");
      onSaved(json);
    } catch {
      toast.error("Something went wrong.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <Dialog open onOpenChange={onClose}>
      <DialogContent className="max-w-md">
        <DialogHeader>
          <DialogTitle>{isEdit ? "Edit Link" : "Add Link"}</DialogTitle>
        </DialogHeader>
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4 mt-2" noValidate>
          {/* Type */}
          <div className="space-y-1.5">
            <Label htmlFor="type">Link Type</Label>
            <select
              id="type"
              className="w-full border border-input rounded-lg px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-ring"
              {...register("type")}
            >
              {LINK_TYPES.map((t) => (
                <option key={t.value} value={t.value}>{t.label}</option>
              ))}
            </select>
          </div>

          {/* Title */}
          <div className="space-y-1.5">
            <Label htmlFor="link-title">Title</Label>
            <Input
              id="link-title"
              placeholder="My Website"
              {...register("title")}
              aria-invalid={!!errors.title}
            />
            {errors.title && (
              <p className="text-xs text-destructive">{errors.title.message}</p>
            )}
          </div>

          {/* URL */}
          <div className="space-y-1.5">
            <Label htmlFor="link-url">URL</Label>
            <Input
              id="link-url"
              placeholder="https://example.com"
              {...register("url")}
              aria-invalid={!!errors.url}
            />
            {errors.url && (
              <p className="text-xs text-destructive">{errors.url.message}</p>
            )}
          </div>

          {/* Description */}
          <div className="space-y-1.5">
            <Label htmlFor="link-desc">Description (optional)</Label>
            <Textarea
              id="link-desc"
              placeholder="Brief description"
              rows={2}
              className="resize-none"
              {...register("description")}
            />
          </div>

          {/* Active */}
          <div className="flex items-center justify-between py-1">
            <Label>Show on profile</Label>
            <Switch
              checked={isActive}
              onCheckedChange={(c) => setValue("isActive", c)}
            />
          </div>

          {/* Actions */}
          <div className="flex gap-3 pt-1">
            <Button type="button" variant="outline" onClick={onClose} disabled={loading} className="flex-1">
              Cancel
            </Button>
            <Button id="save-link-btn" type="submit" disabled={loading} className="flex-1">
              {loading ? (
                <><Loader2 className="w-4 h-4 mr-2 animate-spin" />{isEdit ? "Saving…" : "Adding…"}</>
              ) : (isEdit ? "Save" : "Add Link")}
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
