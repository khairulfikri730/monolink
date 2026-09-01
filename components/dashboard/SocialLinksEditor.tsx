"use client";

import { useState } from "react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import { toast } from "sonner";
import { Plus, Trash2, Loader2, Camera, Video, Users, Briefcase, MessageSquare, Globe } from "lucide-react";

const PLATFORMS = [
  { value: "INSTAGRAM", label: "Instagram", icon: Camera, placeholder: "https://instagram.com/username" },
  { value: "TIKTOK", label: "TikTok", icon: Globe, placeholder: "https://tiktok.com/@username" },
  { value: "YOUTUBE", label: "YouTube", icon: Video, placeholder: "https://youtube.com/@channel" },
  { value: "FACEBOOK", label: "Facebook", icon: Users, placeholder: "https://facebook.com/page" },
  { value: "LINKEDIN", label: "LinkedIn", icon: Briefcase, placeholder: "https://linkedin.com/in/username" },
  { value: "X_TWITTER", label: "X / Twitter", icon: MessageSquare, placeholder: "https://x.com/username" },
];

interface SocialLink {
  id: string;
  platform: string;
  url: string;
}

interface SocialLinksEditorProps {
  profileId: string;
  socialLinks: SocialLink[];
}

export function SocialLinksEditor({ socialLinks: initialLinks }: SocialLinksEditorProps) {
  const [links, setLinks] = useState<SocialLink[]>(initialLinks);
  const [addingPlatform, setAddingPlatform] = useState<string | null>(null);
  const [urlInput, setUrlInput] = useState("");
  const [saving, setSaving] = useState(false);
  const [deletingId, setDeletingId] = useState<string | null>(null);

  const activePlatforms = links.map((l) => l.platform);

  const handleSave = async () => {
    if (!addingPlatform || !urlInput.trim()) return;
    setSaving(true);
    try {
      const res = await fetch("/api/social", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ platform: addingPlatform, url: urlInput.trim() }),
      });
      const json = await res.json();
      if (!res.ok) {
        toast.error(json.error || "Unable to save.");
        return;
      }
      // Update or add
      setLinks((prev) => {
        const exists = prev.find((l) => l.platform === addingPlatform);
        if (exists) return prev.map((l) => l.platform === addingPlatform ? json : l);
        return [...prev, json];
      });
      toast.success("Social link saved.");
      setAddingPlatform(null);
      setUrlInput("");
    } catch {
      toast.error("Something went wrong.");
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: string) => {
    setDeletingId(id);
    try {
      const res = await fetch("/api/social", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      if (!res.ok) {
        toast.error("Unable to delete.");
        return;
      }
      setLinks((prev) => prev.filter((l) => l.id !== id));
      toast.success("Social link removed.");
    } catch {
      toast.error("Something went wrong.");
    } finally {
      setDeletingId(null);
    }
  };

  return (
    <div className="space-y-4">
      {/* Existing */}
      {links.length > 0 && (
        <Card className="border-border shadow-sm overflow-hidden">
          {links.map((link, i) => {
            const platform = PLATFORMS.find((p) => p.value === link.platform);
            const Icon = platform?.icon ?? Globe;
            return (
              <div key={link.id} className={`flex items-center gap-3 px-4 py-3 ${i < links.length - 1 ? "border-b border-border" : ""}`}>
                <div className="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center flex-shrink-0">
                  <Icon className="w-4 h-4 text-neutral-500" />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-neutral-800">{platform?.label}</p>
                  <p className="text-xs text-neutral-400 truncate">{link.url}</p>
                </div>
                <Button
                  id={`delete-social-${link.id}`}
                  variant="ghost"
                  size="sm"
                  className="w-8 h-8 p-0 text-neutral-400 hover:text-destructive hover:bg-red-50"
                  onClick={() => handleDelete(link.id)}
                  disabled={deletingId === link.id}
                >
                  {deletingId === link.id ? <Loader2 className="w-3.5 h-3.5 animate-spin" /> : <Trash2 className="w-3.5 h-3.5" />}
                </Button>
              </div>
            );
          })}
        </Card>
      )}

      {/* Add form */}
      {addingPlatform ? (
        <Card className="p-5 border-border shadow-sm space-y-4">
          <div className="flex items-center justify-between">
            <p className="text-sm font-semibold text-neutral-800">
              {PLATFORMS.find((p) => p.value === addingPlatform)?.label}
            </p>
            <button onClick={() => { setAddingPlatform(null); setUrlInput(""); }} className="text-neutral-400 hover:text-neutral-600 text-xs">
              Cancel
            </button>
          </div>
          <div className="space-y-1.5">
            <Label htmlFor="social-url">Profile URL</Label>
            <Input
              id="social-url"
              value={urlInput}
              onChange={(e) => setUrlInput(e.target.value)}
              placeholder={PLATFORMS.find((p) => p.value === addingPlatform)?.placeholder}
              autoFocus
            />
          </div>
          <Button id="save-social-btn" onClick={handleSave} disabled={saving || !urlInput.trim()}>
            {saving ? <><Loader2 className="w-4 h-4 mr-2 animate-spin" />Saving…</> : "Save"}
          </Button>
        </Card>
      ) : (
        <Card className="p-5 border-border shadow-sm">
          <p className="text-sm font-semibold text-neutral-800 mb-3">Add Platform</p>
          <div className="grid grid-cols-3 sm:grid-cols-6 gap-2">
            {PLATFORMS.map((p) => {
              const active = activePlatforms.includes(p.value);
              const Icon = p.icon;
              return (
                <button
                  key={p.value}
                  id={`add-social-${p.value}`}
                  onClick={() => { setAddingPlatform(p.value); setUrlInput(active ? links.find((l) => l.platform === p.value)?.url ?? "" : ""); }}
                  className="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-border hover:border-brand-300 hover:bg-brand-50 transition-all relative"
                >
                  <Icon className="w-5 h-5 text-neutral-500" />
                  <span className="text-[10px] text-neutral-500 text-center">{p.label}</span>
                  {active && (
                    <span className="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white" />
                  )}
                </button>
              );
            })}
          </div>
        </Card>
      )}

      {links.length === 0 && !addingPlatform && (
        <div className="text-center py-8 text-neutral-400 text-sm">
          No social links yet. Add your first platform above.
        </div>
      )}
    </div>
  );
}
