import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

/**
 * Format a number with thousands separator
 */
export function formatNumber(n: number): string {
  return new Intl.NumberFormat("id-ID").format(n);
}

/**
 * Reserved usernames that cannot be used as profile slugs
 * PRD FR-21
 */
export const RESERVED_USERNAMES = [
  "admin",
  "login",
  "dashboard",
  "api",
  "settings",
  "register",
  "logout",
  "profile",
  "user",
  "users",
  "account",
  "public",
  "static",
  "assets",
  "images",
  "uploads",
];

/**
 * Validate a username:
 * - 3–30 chars
 * - alphanumeric + hyphens + underscores
 * - no leading/trailing hyphen/underscore
 * - not reserved
 */
export function validateUsername(username: string): string | null {
  if (!username) return "Username is required.";
  if (username.length < 3) return "Username must be at least 3 characters.";
  if (username.length > 30) return "Username must be at most 30 characters.";
  if (!/^[a-z0-9_-]+$/.test(username))
    return "Username can only contain letters, numbers, hyphens, and underscores.";
  if (/^[-_]|[-_]$/.test(username))
    return "Username cannot start or end with a hyphen or underscore.";
  if (RESERVED_USERNAMES.includes(username.toLowerCase()))
    return "This username is reserved and cannot be used.";
  return null;
}

/**
 * Validate a URL — allow https, http, mailto, tel. Block javascript:, data:, vbscript:
 * PRD FR-23
 */
export function validateUrl(url: string): boolean {
  if (!url) return false;
  const lower = url.toLowerCase().trim();
  const blocked = ["javascript:", "data:", "vbscript:"];
  if (blocked.some((b) => lower.startsWith(b))) return false;
  const allowed = ["https://", "http://", "mailto:", "tel:", "https://wa.me/"];
  return allowed.some((a) => lower.startsWith(a));
}

/**
 * Build a WhatsApp URL from a phone number
 * PRD FR-09
 */
export function buildWhatsAppUrl(phone: string, message?: string): string {
  // Strip everything that isn't a digit or leading +
  let cleaned = phone.replace(/[^\d+]/g, "");
  if (cleaned.startsWith("0")) cleaned = "62" + cleaned.slice(1);
  if (cleaned.startsWith("+")) cleaned = cleaned.slice(1);
  const base = `https://wa.me/${cleaned}`;
  if (message) return `${base}?text=${encodeURIComponent(message)}`;
  return base;
}

/**
 * Truncate text to a maximum length with ellipsis
 */
export function truncate(text: string, maxLength: number): string {
  if (text.length <= maxLength) return text;
  return text.slice(0, maxLength) + "…";
}
