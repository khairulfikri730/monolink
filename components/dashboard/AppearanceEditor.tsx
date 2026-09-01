"use client";

import { useState, useCallback, useRef } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { themeSchema, type ThemeSchema } from "@/lib/validations";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { Loader2, CheckCircle2 } from "lucide-react";
import { cn } from "@/lib/utils";

const THEME_TEMPLATES = [
  {
    name: "classic",
    label: "Classic",
    preview: { bg: "#ffffff", btn: "#000000", text: "#000000" },
  },
  {
    name: "minimal",
    label: "Minimal",
    preview: { bg: "#f9fafb", btn: "#374151", text: "#111827" },
  },
  {
    name: "modern",
    label: "Modern",
    preview: { bg: "#0f172a", btn: "#6172f3", text: "#f1f5f9" },
  },
  {
    name: "business",
    label: "Business",
    preview: { bg: "#1e3a5f", btn: "#f59e0b", text: "#ffffff" },
  },
  {
    name: "creator",
    label: "Creator",
    preview: { bg: "#fdf4ff", btn: "#a855f7", text: "#581c87" },
  },
];

const BUTTON_STYLES = [
  { value: "ROUNDED", label: "Rounded" },
  { value: "PILL", label: "Pill" },
  { value: "SQUARE", label: "Square" },
  { value: "GLASS", label: "Glass" },
  { value: "OUTLINE", label: "Outline" },
];

const FONT_OPTIONS = [
  "Inter",
  "Plus Jakarta Sans",
  "Roboto",
  "Poppins",
  "Lato",
  "Montserrat",
];

interface AppearanceEditorProps {
  theme: {
    templateName: string;
    backgroundType: string;
    backgroundValue: string;
    primaryColor: string;
    secondaryColor: string;
    textColor: string;
    buttonColor: string;
    buttonTextColor: string;
    buttonStyle: string;
    fontFamily: string;
    fontSize: string;
    fontWeight: string;
    layout: string;
  } | null;
}

type SaveState = "idle" | "saving" | "saved" | "error";

export function AppearanceEditor({ theme }: AppearanceEditorProps) {
  const [saveState, setSaveState] = useState<SaveState>("idle");
  const saveTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const { register, handleSubmit, watch, setValue } = useForm<ThemeSchema>({
    resolver: zodResolver(themeSchema),
    defaultValues: {
      templateName: theme?.templateName ?? "classic",
      backgroundType: (theme?.backgroundType as ThemeSchema["backgroundType"]) ?? "SOLID",
      backgroundValue: theme?.backgroundValue ?? "#ffffff",
      primaryColor: theme?.primaryColor ?? "#000000",
      secondaryColor: theme?.secondaryColor ?? "#666666",
      textColor: theme?.textColor ?? "#000000",
      buttonColor: theme?.buttonColor ?? "#000000",
      buttonTextColor: theme?.buttonTextColor ?? "#ffffff",
      buttonStyle: (theme?.buttonStyle as ThemeSchema["buttonStyle"]) ?? "ROUNDED",
      fontFamily: theme?.fontFamily ?? "Inter",
      fontSize: (theme?.fontSize as ThemeSchema["fontSize"]) ?? "md",
      fontWeight: (theme?.fontWeight as ThemeSchema["fontWeight"]) ?? "normal",
      layout: (theme?.layout as ThemeSchema["layout"]) ?? "center",
    },
  });

  const selectedTemplate = watch("templateName");
  const selectedButtonStyle = watch("buttonStyle");
  const selectedLayout = watch("layout");

  const applyTemplate = (t: typeof THEME_TEMPLATES[0]) => {
    setValue("templateName", t.name);
    setValue("backgroundValue", t.preview.bg);
    setValue("buttonColor", t.preview.btn);
    setValue("textColor", t.preview.text);
    if (t.name === "modern") {
      setValue("backgroundType", "SOLID");
      setValue("buttonStyle", "ROUNDED");
    }
  };

  const save = useCallback(async (data: ThemeSchema) => {
    setSaveState("saving");
    try {
      const res = await fetch("/api/theme", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const json = await res.json();
      if (!res.ok) {
        toast.error(json.error || "Unable to save.");
        setSaveState("error");
        return;
      }
      setSaveState("saved");
      if (saveTimerRef.current) clearTimeout(saveTimerRef.current);
      saveTimerRef.current = setTimeout(() => setSaveState("idle"), 3000);
    } catch {
      toast.error("Something went wrong.");
      setSaveState("error");
    }
  }, []);

  return (
    <form onSubmit={handleSubmit(save)} className="space-y-6">
      {/* Templates */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Theme Templates</h2>
        <div className="grid grid-cols-5 gap-3">
          {THEME_TEMPLATES.map((t) => (
            <button
              key={t.name}
              type="button"
              onClick={() => applyTemplate(t)}
              className={cn(
                "flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition-all",
                selectedTemplate === t.name
                  ? "border-brand-500 bg-brand-50"
                  : "border-border hover:border-neutral-300"
              )}
            >
              <div
                className="w-10 h-10 rounded-lg flex items-center justify-center"
                style={{ background: t.preview.bg, border: "1px solid rgba(0,0,0,0.1)" }}
              >
                <div
                  className="w-6 h-2 rounded-sm"
                  style={{ background: t.preview.btn }}
                />
              </div>
              <span className="text-xs font-medium text-neutral-600 text-center leading-tight">
                {t.label}
              </span>
            </button>
          ))}
        </div>
      </Card>

      {/* Background */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Background</h2>
        <div className="space-y-4">
          <div className="flex gap-2">
            {(["SOLID", "GRADIENT", "IMAGE"] as const).map((type) => (
              <button
                key={type}
                type="button"
                onClick={() => {
                  setValue("backgroundType", type, { shouldDirty: true });
                  const currentVal = watch("backgroundValue");
                  if (type === "GRADIENT" && (!currentVal || currentVal.startsWith("#") || currentVal.startsWith("http"))) {
                    setValue("backgroundValue", "linear-gradient(135deg, #4d52e8 0%, #8098f8 100%)", { shouldDirty: true });
                  } else if (type === "IMAGE" && (!currentVal || currentVal.startsWith("#") || currentVal.startsWith("linear"))) {
                    setValue("backgroundValue", "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1000&auto=format&fit=crop", { shouldDirty: true });
                  } else if (type === "SOLID" && (!currentVal || !currentVal.startsWith("#"))) {
                    setValue("backgroundValue", "#f0f4ff", { shouldDirty: true });
                  }
                }}
                className={cn(
                  "px-4 py-1.5 rounded-lg text-xs font-medium border transition-all",
                  watch("backgroundType") === type
                    ? "border-brand-500 bg-brand-50 text-brand-700"
                    : "border-border text-neutral-600 hover:bg-neutral-50"
                )}
              >
                {type.charAt(0) + type.slice(1).toLowerCase()}
              </button>
            ))}
          </div>
          <div className="flex items-center gap-3">
            {watch("backgroundType") === "SOLID" && (
              <input
                type="color"
                value={watch("backgroundValue").startsWith("#") ? watch("backgroundValue").substring(0, 7) : "#ffffff"}
                onChange={(e) => setValue("backgroundValue", e.target.value, { shouldDirty: true })}
                className="w-10 h-10 rounded-lg border border-input cursor-pointer"
                title="Background color"
              />
            )}
            <input
              type="text"
              {...register("backgroundValue")}
              className="flex-1 border border-input rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-ring outline-none"
              placeholder="#ffffff or gradient CSS"
            />
          </div>
        </div>
      </Card>

      {/* Colors */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Colors</h2>
        <div className="grid sm:grid-cols-2 gap-4">
          {[
            { label: "Text Color", field: "textColor" as const },
            { label: "Button Color", field: "buttonColor" as const },
            { label: "Button Text Color", field: "buttonTextColor" as const },
            { label: "Primary Color", field: "primaryColor" as const },
          ].map(({ label, field }) => (
            <div key={field} className="space-y-1.5">
              <Label>{label}</Label>
              <div className="flex items-center gap-2">
                <input
                  type="color"
                  value={watch(field).startsWith("#") ? watch(field).substring(0, 7) : "#000000"}
                  onChange={(e) => setValue(field, e.target.value, { shouldDirty: true })}
                  className="w-9 h-9 rounded-lg border border-input cursor-pointer flex-shrink-0"
                  title={label}
                />
                <input
                  type="text"
                  {...register(field)}
                  className="flex-1 border border-input rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-ring outline-none"
                  placeholder="#000000"
                />
              </div>
            </div>
          ))}
        </div>
      </Card>

      {/* Button Style */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Button Style</h2>
        <div className="flex gap-2 flex-wrap">
          {BUTTON_STYLES.map((bs) => (
            <button
              key={bs.value}
              type="button"
              onClick={() => setValue("buttonStyle", bs.value as ThemeSchema["buttonStyle"])}
              className={cn(
                "px-4 py-2 text-sm font-medium border transition-all",
                bs.value === "PILL" ? "rounded-full" : bs.value === "SQUARE" ? "rounded-none" : "rounded-lg",
                selectedButtonStyle === bs.value
                  ? "border-brand-500 bg-brand-50 text-brand-700"
                  : "border-border text-neutral-600 hover:bg-neutral-50"
              )}
            >
              {bs.label}
            </button>
          ))}
        </div>
      </Card>

      {/* Typography */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4">Typography</h2>
        <div className="grid sm:grid-cols-2 gap-4">
          <div className="space-y-1.5">
            <Label htmlFor="fontFamily">Font Family</Label>
            <select
              id="fontFamily"
              className="w-full border border-input rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-ring outline-none bg-background"
              {...register("fontFamily")}
            >
              {FONT_OPTIONS.map((f) => (
                <option key={f} value={f} style={{ fontFamily: f }}>{f}</option>
              ))}
            </select>
          </div>
          <div className="space-y-1.5">
            <Label htmlFor="layout">Profile Alignment</Label>
            <div className="flex gap-2">
              {(["center", "left"] as const).map((l) => (
                <button
                  key={l}
                  type="button"
                  onClick={() => setValue("layout", l)}
                  className={cn(
                    "flex-1 py-2 text-sm font-medium border rounded-lg transition-all capitalize",
                    selectedLayout === l
                      ? "border-brand-500 bg-brand-50 text-brand-700"
                      : "border-border text-neutral-600 hover:bg-neutral-50"
                  )}
                >
                  {l}
                </button>
              ))}
            </div>
          </div>
        </div>
      </Card>

      {/* Save */}
      <div className="flex items-center gap-3">
        <Button id="save-appearance-btn" type="submit" disabled={saveState === "saving"}>
          {saveState === "saving" ? (
            <><Loader2 className="w-4 h-4 mr-2 animate-spin" />Saving…</>
          ) : "Save Appearance"}
        </Button>
        {saveState === "saved" && (
          <span className="flex items-center gap-1.5 text-sm text-green-600 animate-fade-in">
            <CheckCircle2 className="w-4 h-4" />Saved
          </span>
        )}
      </div>
    </form>
  );
}
