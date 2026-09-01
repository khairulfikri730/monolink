import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { formatNumber } from "@/lib/utils";
import {
  Eye,
  MousePointerClick,
  Link2,
  ArrowRight,
  TrendingUp,
  Share2,
} from "lucide-react";
import Link from "next/link";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Dashboard" };

export default async function DashboardOverviewPage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    include: {
      links: { where: { isActive: true }, orderBy: { sortOrder: "asc" }, take: 3 },
      theme: true,
      _count: {
        select: {
          links: true,
          analytics: true,
        },
      },
    },
  });

  if (!profile) redirect("/login");

  const [profileViews, linkClicks] = await Promise.all([
    prisma.analyticsEvent.count({
      where: { profileId: profile.id, eventType: "PROFILE_VIEW" },
    }),
    prisma.analyticsEvent.count({
      where: { profileId: profile.id, eventType: "LINK_CLICK" },
    }),
  ]);

  const ctr =
    profileViews > 0 ? ((linkClicks / profileViews) * 100).toFixed(1) : "0.0";

  const isProfileComplete =
    !!profile.bio && !!profile.profileImage && profile._count.links > 0;

  const statCards = [
    {
      label: "Profile Views",
      value: profileViews,
      icon: Eye,
      color: "text-blue-600",
      bg: "bg-blue-50",
    },
    {
      label: "Link Clicks",
      value: linkClicks,
      icon: MousePointerClick,
      color: "text-purple-600",
      bg: "bg-purple-50",
    },
    {
      label: "CTR",
      value: `${ctr}%`,
      icon: TrendingUp,
      color: "text-green-600",
      bg: "bg-green-50",
      raw: true,
    },
    {
      label: "Total Links",
      value: profile._count.links,
      icon: Link2,
      color: "text-brand-600",
      bg: "bg-brand-50",
    },
  ];

  return (
    <div className="space-y-8 animate-fade-in">
      {/* Welcome */}
      <div className="flex items-start justify-between gap-4 flex-wrap">
        <div>
          <h1 className="text-2xl font-bold text-neutral-900">
            Welcome back 👋
          </h1>
          <p className="text-neutral-500 text-sm mt-1">
            Your profile at{" "}
            <a
              href={`/${profile.username}`}
              target="_blank"
              className="text-brand-600 hover:underline font-medium"
            >
              monolink.com/{profile.username}
            </a>
          </p>
        </div>
        <Button id="view-profile-btn" variant="outline" asChild>
          <a href={`/${profile.username}`} target="_blank">
            <Share2 className="w-4 h-4 mr-2" />
            View Profile
          </a>
        </Button>
      </div>

      {/* Profile incomplete banner */}
      {!isProfileComplete && (
        <div className="bg-brand-50 border border-brand-200 rounded-xl p-4 flex items-center justify-between gap-3 flex-wrap">
          <div>
            <p className="text-brand-800 font-medium text-sm">
              Complete your profile to get started
            </p>
            <p className="text-brand-600 text-xs mt-0.5">
              Add a bio, profile photo, and your first link.
            </p>
          </div>
          <Button id="complete-profile-btn" size="sm" asChild>
            <Link href="/dashboard/profile">Set up Profile</Link>
          </Button>
        </div>
      )}

      {/* Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {statCards.map((s) => (
          <Card
            key={s.label}
            className="p-5 border-border shadow-sm hover:shadow-md transition-shadow"
          >
            <div className={`w-9 h-9 rounded-xl ${s.bg} flex items-center justify-center mb-3`}>
              <s.icon className={`w-4 h-4 ${s.color}`} />
            </div>
            <p className="text-2xl font-bold text-neutral-900">
              {s.raw ? s.value : formatNumber(Number(s.value))}
            </p>
            <p className="text-xs text-neutral-500 mt-0.5">{s.label}</p>
          </Card>
        ))}
      </div>

      {/* Quick Actions */}
      <div className="grid sm:grid-cols-3 gap-4">
        {[
          { href: "/dashboard/profile", label: "Edit Profile", icon: "👤", desc: "Update your info and photo" },
          { href: "/dashboard/links", label: "Manage Links", icon: "🔗", desc: "Add or reorder your links" },
          { href: "/dashboard/appearance", label: "Customize Look", icon: "🎨", desc: "Change colors and style" },
        ].map((qa) => (
          <Card
            key={qa.href}
            className="p-5 border-border shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 cursor-pointer group"
          >
            <Link href={qa.href} className="flex flex-col gap-2">
              <span className="text-2xl">{qa.icon}</span>
              <div className="flex items-center justify-between">
                <p className="font-semibold text-neutral-800 text-sm">{qa.label}</p>
                <ArrowRight className="w-4 h-4 text-neutral-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" />
              </div>
              <p className="text-xs text-neutral-400">{qa.desc}</p>
            </Link>
          </Card>
        ))}
      </div>

      {/* Recent Links */}
      {profile.links.length > 0 && (
        <div>
          <div className="flex items-center justify-between mb-3">
            <h2 className="font-semibold text-neutral-800 text-base">Active Links</h2>
            <Link href="/dashboard/links" className="text-xs text-brand-600 hover:text-brand-700 font-medium">
              Manage all →
            </Link>
          </div>
          <Card className="border-border shadow-sm overflow-hidden">
            {profile.links.map((link, i) => (
              <div
                key={link.id}
                className={`flex items-center gap-3 px-4 py-3 ${i < profile.links.length - 1 ? "border-b border-border" : ""}`}
              >
                <div className="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center flex-shrink-0">
                  <Link2 className="w-3.5 h-3.5 text-neutral-500" />
                </div>
                <div className="min-w-0 flex-1">
                  <p className="text-sm font-medium text-neutral-800 truncate">{link.title}</p>
                  <p className="text-xs text-neutral-400 truncate">{link.url}</p>
                </div>
                <Badge className="bg-green-50 text-green-700 border-green-200 text-xs flex-shrink-0">
                  Active
                </Badge>
              </div>
            ))}
          </Card>
        </div>
      )}
    </div>
  );
}
