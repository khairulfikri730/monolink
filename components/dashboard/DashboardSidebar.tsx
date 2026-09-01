"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { signOut } from "next-auth/react";
import {
  LayoutDashboard,
  User,
  Link2,
  Palette,
  Share2,
  BarChart2,
  Settings,
  LogOut,
  ChevronRight,
  AtSign,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";

const navItems = [
  { href: "/dashboard", label: "Overview", icon: LayoutDashboard, exact: true },
  { href: "/dashboard/profile", label: "Profile", icon: User, exact: false },
  { href: "/dashboard/links", label: "Links", icon: Link2, exact: false },
  { href: "/dashboard/social", label: "Social Media", icon: AtSign, exact: false },
  { href: "/dashboard/appearance", label: "Appearance", icon: Palette, exact: false },
  { href: "/dashboard/analytics", label: "Analytics", icon: BarChart2, exact: false },
  { href: "/dashboard/settings", label: "Settings", icon: Settings, exact: false },
];

interface DashboardSidebarProps {
  username?: string;
}

export function DashboardSidebar({ username }: DashboardSidebarProps) {
  const pathname = usePathname();

  const isActive = (href: string, exact: boolean) =>
    exact ? pathname === href : pathname.startsWith(href);

  return (
    <aside className="hidden lg:flex flex-col h-screen w-[260px] border-r border-border bg-white sticky top-0 flex-shrink-0">
      {/* Logo */}
      <div className="flex items-center gap-3 px-6 py-5 border-b border-border">
        <div
          className="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
          style={{ background: "linear-gradient(135deg, #4d52e8, #8098f8)" }}
        >
          <Link2 className="w-4 h-4 text-white" />
        </div>
        <div className="min-w-0">
          <p className="font-bold text-sm text-neutral-900 leading-tight">MonoLink</p>
          {username && (
            <a
              href={`/${username}`}
              target="_blank"
              className="text-[11px] text-brand-600 hover:text-brand-700 truncate block"
            >
              /{username}
            </a>
          )}
        </div>
      </div>

      {/* Nav */}
      <nav className="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        {navItems.map((item) => {
          const active = isActive(item.href, item.exact);
          return (
            <Link
              key={item.href}
              href={item.href}
              className={cn(
                "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group",
                active
                  ? "bg-brand-50 text-brand-700"
                  : "text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900"
              )}
            >
              <item.icon
                className={cn(
                  "w-4 h-4 flex-shrink-0 transition-colors",
                  active
                    ? "text-brand-600"
                    : "text-neutral-400 group-hover:text-neutral-600"
                )}
              />
              {item.label}
              {active && (
                <ChevronRight className="w-3.5 h-3.5 ml-auto text-brand-400" />
              )}
            </Link>
          );
        })}
      </nav>

      {/* Share + Logout */}
      <div className="px-3 py-4 border-t border-border space-y-1">
        {username && (
          <Button
            id="sidebar-share-btn"
            variant="ghost"
            className="w-full justify-start gap-3 text-neutral-600 hover:text-brand-600 hover:bg-brand-50 text-sm"
            asChild
          >
            <a href={`/${username}`} target="_blank">
              <Share2 className="w-4 h-4" />
              View Profile
            </a>
          </Button>
        )}
        <Button
          id="user-logout-btn"
          variant="ghost"
          className="w-full justify-start gap-3 text-neutral-600 hover:text-destructive hover:bg-red-50 text-sm"
          onClick={() => signOut({ callbackUrl: "/login" })}
        >
          <LogOut className="w-4 h-4" />
          Sign out
        </Button>
      </div>
    </aside>
  );
}
