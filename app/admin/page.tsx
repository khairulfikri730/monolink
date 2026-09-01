import { prisma } from "@/lib/db";
import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { Users, UserCheck, UserX, Eye, MousePointerClick } from "lucide-react";
import { Card } from "@/components/ui/card";
import { formatNumber } from "@/lib/utils";
import Link from "next/link";
import { Badge } from "@/components/ui/badge";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Admin Overview" };

async function getStats() {
  const [totalUsers, activeUsers, inactiveUsers, totalViews, totalClicks] =
    await Promise.all([
      prisma.user.count({ where: { role: "USER" } }),
      prisma.user.count({ where: { role: "USER", status: "ACTIVE" } }),
      prisma.user.count({ where: { role: "USER", status: "INACTIVE" } }),
      prisma.analyticsEvent.count({ where: { eventType: "PROFILE_VIEW" } }),
      prisma.analyticsEvent.count({ where: { eventType: "LINK_CLICK" } }),
    ]);
  return { totalUsers, activeUsers, inactiveUsers, totalViews, totalClicks };
}

async function getRecentUsers() {
  return prisma.user.findMany({
    where: { role: "USER" },
    orderBy: { createdAt: "desc" },
    take: 5,
    select: {
      id: true,
      name: true,
      email: true,
      status: true,
      createdAt: true,
      profile: { select: { username: true } },
    },
  });
}

export default async function AdminOverviewPage() {
  const session = await auth();
  if (!session?.user || session.user.role !== "ADMIN") redirect("/login");

  const [stats, recentUsers] = await Promise.all([
    getStats(),
    getRecentUsers(),
  ]);

  const statCards = [
    {
      label: "Total Users",
      value: stats.totalUsers,
      icon: Users,
      color: "text-brand-600",
      bg: "bg-brand-50",
    },
    {
      label: "Active Users",
      value: stats.activeUsers,
      icon: UserCheck,
      color: "text-green-600",
      bg: "bg-green-50",
    },
    {
      label: "Inactive Users",
      value: stats.inactiveUsers,
      icon: UserX,
      color: "text-neutral-500",
      bg: "bg-neutral-100",
    },
    {
      label: "Profile Views",
      value: stats.totalViews,
      icon: Eye,
      color: "text-blue-600",
      bg: "bg-blue-50",
    },
    {
      label: "Link Clicks",
      value: stats.totalClicks,
      icon: MousePointerClick,
      color: "text-purple-600",
      bg: "bg-purple-50",
    },
  ];

  return (
    <div className="space-y-8 animate-fade-in">
      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Overview</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Platform statistics and recent activity.
        </p>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {statCards.map((s) => (
          <Card
            key={s.label}
            className="p-5 border-border shadow-sm hover:shadow-md transition-shadow"
          >
            <div className={`w-9 h-9 rounded-xl ${s.bg} flex items-center justify-center mb-3`}>
              <s.icon className={`w-4 h-4 ${s.color}`} />
            </div>
            <p className="text-2xl font-bold text-neutral-900">
              {formatNumber(s.value)}
            </p>
            <p className="text-xs text-neutral-500 mt-0.5">{s.label}</p>
          </Card>
        ))}
      </div>

      {/* Recent Users */}
      <div>
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-base font-semibold text-neutral-900">
            Recent Users
          </h2>
          <Link
            href="/admin/users"
            className="text-xs text-brand-600 hover:text-brand-700 font-medium"
          >
            View all →
          </Link>
        </div>
        <Card className="border-border shadow-sm overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-neutral-50">
                  <th className="text-left px-4 py-3 font-medium text-neutral-500 text-xs uppercase tracking-wide">
                    Name
                  </th>
                  <th className="text-left px-4 py-3 font-medium text-neutral-500 text-xs uppercase tracking-wide hidden sm:table-cell">
                    Username
                  </th>
                  <th className="text-left px-4 py-3 font-medium text-neutral-500 text-xs uppercase tracking-wide hidden md:table-cell">
                    Email
                  </th>
                  <th className="text-left px-4 py-3 font-medium text-neutral-500 text-xs uppercase tracking-wide">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                {recentUsers.length === 0 && (
                  <tr>
                    <td
                      colSpan={4}
                      className="text-center py-10 text-neutral-400 text-sm"
                    >
                      No users yet.{" "}
                      <Link
                        href="/admin/users/new"
                        className="text-brand-600 hover:underline"
                      >
                        Create first user
                      </Link>
                    </td>
                  </tr>
                )}
                {recentUsers.map((u) => (
                  <tr
                    key={u.id}
                    className="border-b border-border last:border-0 hover:bg-neutral-50 transition-colors"
                  >
                    <td className="px-4 py-3 font-medium text-neutral-800">
                      {u.name}
                    </td>
                    <td className="px-4 py-3 text-neutral-500 hidden sm:table-cell">
                      {u.profile?.username ?? "—"}
                    </td>
                    <td className="px-4 py-3 text-neutral-500 hidden md:table-cell">
                      {u.email}
                    </td>
                    <td className="px-4 py-3">
                      <Badge
                        variant={u.status === "ACTIVE" ? "default" : "secondary"}
                        className={
                          u.status === "ACTIVE"
                            ? "bg-green-50 text-green-700 border-green-200"
                            : "bg-neutral-100 text-neutral-500 border-neutral-200"
                        }
                      >
                        {u.status === "ACTIVE" ? "Active" : "Inactive"}
                      </Badge>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>
      </div>
    </div>
  );
}
