"use client";

import { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { signOut } from "next-auth/react";
import {
  Menu,
  X,
  Link2,
  LayoutDashboard,
  User,
  Palette,
  BarChart2,
  Settings,
  Share2,
  AtSign,
} from "lucide-react";
import { cn } from "@/lib/utils";

const navItems = [
  { href: "/dashboard", label: "Overview", icon: LayoutDashboard, exact: true },
  { href: "/dashboard/profile", label: "Profile", icon: User, exact: false },
  { href: "/dashboard/links", label: "Links", icon: Link2, exact: false },
  { href: "/dashboard/social", label: "Social Media", icon: AtSign, exact: false },
  { href: "/dashboard/appearance", label: "Appearance", icon: Palette, exact: false },
  { href: "/dashboard/analytics", label: "Analytics", icon: BarChart2, exact: false },
  { href: "/dashboard/settings", label: "Settings", icon: Settings, exact: false },
];

export function DashboardMobileNav({ username }: { username?: string }) {
  const [open, setOpen] = useState(false);
  const pathname = usePathname();

  const isActive = (href: string, exact: boolean) =>
    exact ? pathname === href : pathname.startsWith(href);

  return (
    <header className="lg:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-border sticky top-0 z-40">
      <div className="flex items-center gap-2">
        <div className="w-7 h-7 rounded-lg bg-brand-600 flex items-center justify-center">
          <Link2 className="w-3.5 h-3.5 text-white" />
        </div>
        <span className="font-bold text-sm text-neutral-900">MonoLink</span>
      </div>
      <button
        id="mobile-menu-toggle"
        onClick={() => setOpen(!open)}
        className="p-1.5 rounded-lg text-neutral-600 hover:bg-neutral-100 transition-colors"
        aria-label="Toggle menu"
      >
        {open ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
      </button>

      {/* Drawer */}
      {open && (
        <>
          <div
            className="fixed inset-0 bg-black/40 z-40"
            onClick={() => setOpen(false)}
          />
          <nav className="fixed top-0 right-0 h-full w-72 bg-white z-50 shadow-xl flex flex-col animate-slide-in">
            <div className="flex items-center justify-between px-5 py-4 border-b border-border">
              <span className="font-bold text-neutral-900">Menu</span>
              <button onClick={() => setOpen(false)}>
                <X className="w-5 h-5 text-neutral-500" />
              </button>
            </div>
            <div className="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
              {navItems.map((item) => {
                const active = isActive(item.href, item.exact);
                return (
                  <Link
                    key={item.href}
                    href={item.href}
                    onClick={() => setOpen(false)}
                    className={cn(
                      "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all",
                      active
                        ? "bg-brand-50 text-brand-700"
                        : "text-neutral-600 hover:bg-neutral-50"
                    )}
                  >
                    <item.icon className={cn("w-4 h-4", active ? "text-brand-600" : "text-neutral-400")} />
                    {item.label}
                  </Link>
                );
              })}
            </div>
            <div className="px-3 py-4 border-t border-border space-y-1">
              {username && (
                <a
                  href={`/${username}`}
                  target="_blank"
                  className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 hover:bg-brand-50 hover:text-brand-600"
                >
                  <Share2 className="w-4 h-4" />
                  View Profile
                </a>
              )}
              <button
                onClick={() => signOut({ callbackUrl: "/login" })}
                className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 hover:bg-red-50 hover:text-destructive w-full"
              >
                <LogOut className="w-4 h-4" />
                Sign out
              </button>
            </div>
          </nav>
        </>
      )}
    </header>
  );
}
