import { z } from "zod";
import { RESERVED_USERNAMES } from "@/lib/utils";

// ---------------------------------------------------------------------------
// Auth
// ---------------------------------------------------------------------------
export const loginSchema = z.object({
  email: z.string().email("Invalid email address."),
  password: z.string().min(1, "Password is required."),
});

export const changePasswordSchema = z
  .object({
    currentPassword: z.string().min(1, "Current password is required."),
    newPassword: z
      .string()
      .min(8, "Password must be at least 8 characters.")
      .max(100),
    confirmPassword: z.string().min(1, "Please confirm your password."),
  })
  .refine((data) => data.newPassword === data.confirmPassword, {
    message: "Passwords do not match.",
    path: ["confirmPassword"],
  });

// ---------------------------------------------------------------------------
// Admin: Create / Edit User
// ---------------------------------------------------------------------------
export const createUserSchema = z.object({
  name: z.string().min(2, "Name must be at least 2 characters.").max(100),
  email: z.string().email("Invalid email address."),
  username: z
    .string()
    .min(3, "Username must be at least 3 characters.")
    .max(30, "Username must be at most 30 characters.")
    .regex(
      /^[a-z0-9_-]+$/,
      "Username can only contain lowercase letters, numbers, hyphens, and underscores."
    )
    .refine((v) => !RESERVED_USERNAMES.includes(v.toLowerCase()), {
      message: "This username is reserved.",
    }),
  password: z.string().min(8, "Password must be at least 8 characters."),
  status: z.enum(["ACTIVE", "INACTIVE"]).default("ACTIVE"),
});

export const editUserSchema = z.object({
  name: z.string().min(2).max(100),
  email: z.string().email("Invalid email address."),
  status: z.enum(["ACTIVE", "INACTIVE"]),
});

export const resetPasswordSchema = z.object({
  newPassword: z.string().min(8, "Password must be at least 8 characters."),
});

// ---------------------------------------------------------------------------
// Profile
// ---------------------------------------------------------------------------
const urlOrEmpty = z
  .string()
  .max(2000)
  .refine(
    (v) =>
      !v ||
      v.startsWith("https://") ||
      v.startsWith("http://") ||
      v.startsWith("mailto:") ||
      v.startsWith("tel:"),
    { message: "Invalid URL." }
  )
  .optional()
  .or(z.literal(""));

export const profileSchema = z.object({
  displayName: z
    .string()
    .min(1, "Display name is required.")
    .max(100, "Display name is too long."),
  username: z
    .string()
    .min(3, "Username must be at least 3 characters.")
    .max(30, "Username must be at most 30 characters.")
    .regex(
      /^[a-z0-9_-]+$/,
      "Username can only contain lowercase letters, numbers, hyphens, and underscores."
    )
    .refine((v) => !RESERVED_USERNAMES.includes(v.toLowerCase()), {
      message: "This username is reserved.",
    }),
  bio: z.string().max(500, "Bio must be at most 500 characters.").optional().or(z.literal("")),
  location: z.string().max(100).optional().or(z.literal("")),
  website: urlOrEmpty,
});

// ---------------------------------------------------------------------------
// Link
// ---------------------------------------------------------------------------
const safeUrl = z
  .string()
  .min(1, "URL is required.")
  .max(2000)
  .refine(
    (v) => {
      const lower = v.toLowerCase();
      const blocked = ["javascript:", "data:", "vbscript:"];
      if (blocked.some((b) => lower.startsWith(b))) return false;
      const allowed = [
        "https://",
        "http://",
        "mailto:",
        "tel:",
        "https://wa.me/",
      ];
      return allowed.some((a) => lower.startsWith(a));
    },
    { message: "Invalid or disallowed URL." }
  );

export const linkSchema = z.object({
  title: z.string().min(1, "Title is required.").max(100, "Title is too long."),
  url: safeUrl,
  type: z.string().default("CUSTOM"),
  icon: z.string().max(50).optional().or(z.literal("")),
  description: z.string().max(200).optional().or(z.literal("")),
  isActive: z.boolean().default(true),
});

export const reorderLinksSchema = z.object({
  linkIds: z.array(z.string()),
});

// ---------------------------------------------------------------------------
// Social Link
// ---------------------------------------------------------------------------
export const socialLinkSchema = z.object({
  platform: z.enum([
    "INSTAGRAM",
    "TIKTOK",
    "YOUTUBE",
    "FACEBOOK",
    "LINKEDIN",
    "X_TWITTER",
  ]),
  url: safeUrl,
});

// ---------------------------------------------------------------------------
// Theme
// ---------------------------------------------------------------------------
export const themeSchema = z.object({
  templateName: z.string().max(50).default("classic"),
  backgroundType: z.enum(["SOLID", "GRADIENT", "IMAGE"]).default("SOLID"),
  backgroundValue: z.string().max(500).default("#ffffff"),
  primaryColor: z.string().max(20).default("#000000"),
  secondaryColor: z.string().max(20).default("#666666"),
  textColor: z.string().max(20).default("#000000"),
  buttonColor: z.string().max(20).default("#000000"),
  buttonTextColor: z.string().max(20).default("#ffffff"),
  buttonStyle: z
    .enum(["ROUNDED", "PILL", "SQUARE", "GLASS", "OUTLINE"])
    .default("ROUNDED"),
  fontFamily: z.string().max(50).default("Inter"),
  fontSize: z.enum(["sm", "md", "lg"]).default("md"),
  fontWeight: z.enum(["normal", "medium", "bold"]).default("normal"),
  layout: z.enum(["center", "left"]).default("center"),
});

export type LoginSchema = z.infer<typeof loginSchema>;
export type CreateUserSchema = z.infer<typeof createUserSchema>;
export type EditUserSchema = z.infer<typeof editUserSchema>;
export type ProfileSchema = z.infer<typeof profileSchema>;
export type LinkSchema = z.infer<typeof linkSchema>;
export type SocialLinkSchema = z.infer<typeof socialLinkSchema>;
export type ThemeSchema = z.infer<typeof themeSchema>;
