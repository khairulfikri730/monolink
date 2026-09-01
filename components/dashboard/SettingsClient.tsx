"use client";

import { useState, useRef } from "react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { Copy, Share2, Download, Eye, EyeOff, Loader2, CheckCircle2 } from "lucide-react";
import { QRCodeCanvas } from "qrcode.react";

interface SettingsClientProps {
  profileUrl: string;
  username: string;
  userName: string;
  userEmail: string;
  userId: string;
}

export function SettingsClient({
  profileUrl,
  username,
  userName,
  userEmail,
  userId,
}: SettingsClientProps) {
  const [copied, setCopied] = useState(false);
  const [showCurrentPw, setShowCurrentPw] = useState(false);
  const [showNewPw, setShowNewPw] = useState(false);
  const [pwLoading, setPwLoading] = useState(false);
  const [pwSaved, setPwSaved] = useState(false);
  const [currentPw, setCurrentPw] = useState("");
  const [newPw, setNewPw] = useState("");
  const [confirmPw, setConfirmPw] = useState("");
  const [pwError, setPwError] = useState("");
  const qrRef = useRef<HTMLDivElement>(null);

  const handleCopy = () => {
    navigator.clipboard.writeText(profileUrl).then(() => {
      setCopied(true);
      toast.success("URL copied to clipboard!");
      setTimeout(() => setCopied(false), 2000);
    });
  };

  const handleShare = async () => {
    if (navigator.share) {
      await navigator.share({
        title: `${userName}'s Profile`,
        text: "Check out my digital profile!",
        url: profileUrl,
      });
    } else {
      handleCopy();
    }
  };

  const handleDownloadQR = () => {
    const canvas = qrRef.current?.querySelector("canvas");
    if (!canvas) return;
    const url = canvas.toDataURL("image/png");
    const a = document.createElement("a");
    a.href = url;
    a.download = `${username}-qrcode.png`;
    a.click();
  };

  const handleChangePassword = async () => {
    setPwError("");
    if (!currentPw || !newPw || !confirmPw) {
      setPwError("All fields are required.");
      return;
    }
    if (newPw.length < 8) {
      setPwError("New password must be at least 8 characters.");
      return;
    }
    if (newPw !== confirmPw) {
      setPwError("Passwords do not match.");
      return;
    }
    setPwLoading(true);
    try {
      const res = await fetch("/api/settings/password", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ currentPassword: currentPw, newPassword: newPw }),
      });
      const json = await res.json();
      if (!res.ok) {
        setPwError(json.error || "Unable to change password.");
        return;
      }
      toast.success("Password changed successfully.");
      setPwSaved(true);
      setCurrentPw(""); setNewPw(""); setConfirmPw("");
      setTimeout(() => setPwSaved(false), 3000);
    } catch {
      setPwError("Something went wrong.");
    } finally {
      setPwLoading(false);
    }
  };

  return (
    <div className="space-y-6 max-w-xl">
      {/* Profile URL & Share */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Your Profile URL</h2>
        <div className="flex gap-2 mb-4">
          <Input value={profileUrl} readOnly className="flex-1 bg-neutral-50 text-sm" />
          <Button id="copy-url-btn" variant="outline" onClick={handleCopy}>
            {copied ? <CheckCircle2 className="w-4 h-4 text-green-600" /> : <Copy className="w-4 h-4" />}
          </Button>
          <Button id="share-profile-btn" variant="outline" onClick={handleShare}>
            <Share2 className="w-4 h-4" />
          </Button>
        </div>
        <a
          href={profileUrl}
          target="_blank"
          className="inline-flex items-center gap-1.5 text-xs text-brand-600 hover:text-brand-700"
        >
          <Eye className="w-3.5 h-3.5" />
          View public profile
        </a>
      </Card>

      {/* QR Code */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">QR Code</h2>
        <div className="flex flex-col sm:flex-row items-start gap-6">
          <div ref={qrRef} className="p-3 rounded-xl border border-border bg-white inline-block">
            <QRCodeCanvas value={profileUrl} size={160} level="M" />
          </div>
          <div className="flex flex-col gap-3">
            <p className="text-sm text-neutral-600">
              Download this QR Code and add it to your business card, poster, or banner.
            </p>
            <Button id="download-qr-btn" variant="outline" onClick={handleDownloadQR}>
              <Download className="w-4 h-4 mr-2" />
              Download QR Code
            </Button>
            <div className="flex flex-wrap gap-2">
              {["Business Card", "Poster", "Banner", "Packaging"].map((u) => (
                <span key={u} className="text-xs text-neutral-400 border border-neutral-200 rounded-full px-2.5 py-0.5">
                  {u}
                </span>
              ))}
            </div>
          </div>
        </div>
      </Card>

      {/* Account Info */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Account</h2>
        <div className="space-y-3">
          <div>
            <Label className="text-xs text-neutral-400">Name</Label>
            <p className="text-sm font-medium text-neutral-800 mt-0.5">{userName}</p>
          </div>
          <div>
            <Label className="text-xs text-neutral-400">Email</Label>
            <p className="text-sm font-medium text-neutral-800 mt-0.5">{userEmail}</p>
          </div>
          <div>
            <Label className="text-xs text-neutral-400">User ID</Label>
            <p className="text-xs text-neutral-400 font-mono mt-0.5">{userId}</p>
          </div>
        </div>
      </Card>

      {/* Change Password */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Change Password</h2>
        <div className="space-y-4">
          {pwError && (
            <div className="text-sm text-destructive bg-red-50 border border-red-100 rounded-lg px-4 py-3">
              {pwError}
            </div>
          )}
          <div className="space-y-1.5">
            <Label htmlFor="current-pw">Current Password</Label>
            <div className="relative">
              <Input
                id="current-pw"
                type={showCurrentPw ? "text" : "password"}
                value={currentPw}
                onChange={(e) => setCurrentPw(e.target.value)}
                className="pr-10"
              />
              <button
                type="button"
                onClick={() => setShowCurrentPw(!showCurrentPw)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600"
              >
                {showCurrentPw ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>
          <div className="space-y-1.5">
            <Label htmlFor="new-pw">New Password</Label>
            <div className="relative">
              <Input
                id="new-pw"
                type={showNewPw ? "text" : "password"}
                value={newPw}
                onChange={(e) => setNewPw(e.target.value)}
                className="pr-10"
                placeholder="Min. 8 characters"
              />
              <button
                type="button"
                onClick={() => setShowNewPw(!showNewPw)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600"
              >
                {showNewPw ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>
          <div className="space-y-1.5">
            <Label htmlFor="confirm-pw">Confirm New Password</Label>
            <Input
              id="confirm-pw"
              type="password"
              value={confirmPw}
              onChange={(e) => setConfirmPw(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-3">
            <Button id="change-pw-btn" onClick={handleChangePassword} disabled={pwLoading}>
              {pwLoading ? <><Loader2 className="w-4 h-4 mr-2 animate-spin" />Changing…</> : "Change Password"}
            </Button>
            {pwSaved && (
              <span className="flex items-center gap-1.5 text-sm text-green-600">
                <CheckCircle2 className="w-4 h-4" />Saved
              </span>
            )}
          </div>
        </div>
      </Card>
    </div>
  );
}
