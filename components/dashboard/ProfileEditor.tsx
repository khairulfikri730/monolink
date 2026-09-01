"use client";

import { useState, useRef, useCallback } from "react";
import { useRouter } from "next/navigation";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { profileSchema, type ProfileSchema } from "@/lib/validations";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Card } from "@/components/ui/card";
import { toast } from "sonner";
import { Camera, Loader2, CheckCircle2, Upload } from "lucide-react";
import Image from "next/image";

interface Profile {
  username: string;
  displayName: string;
  bio: string | null;
  profileImage: string | null;
  logo: string | null;
  location: string | null;
  website: string | null;
}

interface ProfileEditorProps {
  profile: Profile;
}

type SaveState = "idle" | "saving" | "saved" | "error";

export function ProfileEditor({ profile }: ProfileEditorProps) {
  const router = useRouter();
  const [saveState, setSaveState] = useState<SaveState>("idle");
  const [profileImageUrl, setProfileImageUrl] = useState(profile.profileImage || "");
  const [uploadingImage, setUploadingImage] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const saveTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors, isDirty },
  } = useForm<ProfileSchema>({
    resolver: zodResolver(profileSchema),
    defaultValues: {
      displayName: profile.displayName,
      username: profile.username,
      bio: profile.bio || "",
      location: profile.location || "",
      website: profile.website || "",
    },
  });

  const save = useCallback(async (data: ProfileSchema) => {
    setSaveState("saving");
    try {
      const res = await fetch("/api/profile", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const json = await res.json();
      if (!res.ok) {
        toast.error(json.error || "Unable to save changes.");
        setSaveState("error");
        return;
      }
      setSaveState("saved");
      router.refresh();
      if (saveTimerRef.current) clearTimeout(saveTimerRef.current);
      saveTimerRef.current = setTimeout(() => setSaveState("idle"), 3000);
    } catch {
      toast.error("Something went wrong. Please try again.");
      setSaveState("error");
    }
  }, [router]);

  const handleImageUpload = async (file: File) => {
    setUploadingImage(true);
    try {
      const formData = new FormData();
      formData.append("file", file);
      formData.append("type", "profileImage");
      const res = await fetch("/api/upload", { method: "POST", body: formData });
      const json = await res.json();
      if (!res.ok) {
        toast.error(json.error || "Upload failed.");
        return;
      }
      setProfileImageUrl(json.url);
      toast.success("Profile photo updated.");
      router.refresh();
    } catch {
      toast.error("Upload failed.");
    } finally {
      setUploadingImage(false);
    }
  };

  return (
    <div className="space-y-6">
      {/* Photo Upload */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Profile Photo</h2>
        <div className="flex items-center gap-5">
          <div className="relative flex-shrink-0">
            <div className="w-20 h-20 rounded-full bg-neutral-100 overflow-hidden ring-4 ring-white shadow-md">
              {profileImageUrl ? (
                <Image
                  src={profileImageUrl}
                  alt="Profile"
                  width={80}
                  height={80}
                  className="w-full h-full object-cover"
                />
              ) : (
                <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-100 to-brand-200">
                  <span className="text-brand-700 text-2xl font-bold">
                    {profile.displayName?.[0]?.toUpperCase() ?? "?"}
                  </span>
                </div>
              )}
            </div>
            {uploadingImage && (
              <div className="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center">
                <Loader2 className="w-5 h-5 text-white animate-spin" />
              </div>
            )}
          </div>
          <div>
            <Button
              id="upload-photo-btn"
              variant="outline"
              size="sm"
              onClick={() => fileInputRef.current?.click()}
              disabled={uploadingImage}
            >
              <Upload className="w-4 h-4 mr-2" />
              Upload Photo
            </Button>
            <p className="text-xs text-neutral-400 mt-1.5">
              JPG, PNG, WebP up to 5MB
            </p>
          </div>
          <input
            ref={fileInputRef}
            type="file"
            accept="image/jpeg,image/png,image/webp,image/gif"
            className="sr-only"
            onChange={(e) => {
              const file = e.target.files?.[0];
              if (file) handleImageUpload(file);
            }}
          />
        </div>
      </Card>

      {/* Profile Info Form */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Profile Information</h2>
        <form onSubmit={handleSubmit(save)} className="space-y-5" noValidate>
          <div className="grid sm:grid-cols-2 gap-5">
            <div className="space-y-1.5">
              <Label htmlFor="displayName">Display Name</Label>
              <Input
                id="displayName"
                placeholder="Aura Ashel"
                {...register("displayName")}
                aria-invalid={!!errors.displayName}
              />
              {errors.displayName && (
                <p className="text-xs text-destructive">{errors.displayName.message}</p>
              )}
            </div>

            <div className="space-y-1.5">
              <Label htmlFor="username">Username</Label>
              <div className="flex items-center border border-input rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-ring">
                <span className="bg-neutral-50 border-r border-input px-3 py-2 text-neutral-400 text-sm select-none whitespace-nowrap">
                  site.com/
                </span>
                <input
                  id="username"
                  className="flex-1 px-3 py-2 text-sm bg-transparent outline-none min-w-0"
                  placeholder="aura-ashel"
                  {...register("username", {
                    onChange: (e) => {
                      e.target.value = e.target.value.toLowerCase().replace(/[^a-z0-9_-]/g, "");
                    },
                  })}
                  aria-invalid={!!errors.username}
                />
              </div>
              {errors.username && (
                <p className="text-xs text-destructive">{errors.username.message}</p>
              )}
            </div>
          </div>

          <div className="space-y-1.5">
            <Label htmlFor="bio">Bio</Label>
            <Textarea
              id="bio"
              placeholder="Digital Creator & Entrepreneur"
              rows={3}
              className="resize-none"
              {...register("bio")}
            />
            <p className="text-xs text-neutral-400">
              Brief description about yourself. Max 500 characters.
            </p>
          </div>

          <div className="grid sm:grid-cols-2 gap-5">
            <div className="space-y-1.5">
              <Label htmlFor="location">Location</Label>
              <Input
                id="location"
                placeholder="Padang, Indonesia"
                {...register("location")}
              />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="website">Website</Label>
              <Input
                id="website"
                placeholder="https://yourwebsite.com"
                {...register("website")}
                aria-invalid={!!errors.website}
              />
              {errors.website && (
                <p className="text-xs text-destructive">{errors.website.message}</p>
              )}
            </div>
          </div>

          {/* Save */}
          <div className="flex items-center gap-3 pt-2">
            <Button id="save-profile-btn" type="submit" disabled={saveState === "saving"}>
              {saveState === "saving" ? (
                <>
                  <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                  Saving…
                </>
              ) : "Save Changes"}
            </Button>
            {saveState === "saved" && (
              <span className="flex items-center gap-1.5 text-sm text-green-600 animate-fade-in">
                <CheckCircle2 className="w-4 h-4" />
                Saved
              </span>
            )}
            {saveState === "error" && (
              <span className="text-sm text-destructive">Unable to save changes.</span>
            )}
          </div>
        </form>
      </Card>
    </div>
  );
}
