import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { AnalyticsDisplay } from "@/components/dashboard/AnalyticsDisplay";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Analytics" };

export default async function AnalyticsPage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    select: { id: true, username: true },
  });

  if (!profile) redirect("/login");

  // Get last 30 days analytics
  const thirtyDaysAgo = new Date();
  thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

  const [totalViews, totalClicks, topLinks, dailyViews] = await Promise.all([
    prisma.analyticsEvent.count({
      where: { profileId: profile.id, eventType: "PROFILE_VIEW" },
    }),
    prisma.analyticsEvent.count({
      where: { profileId: profile.id, eventType: "LINK_CLICK" },
    }),
    // Top links by clicks
    prisma.analyticsEvent.groupBy({
      by: ["linkId"],
      where: { profileId: profile.id, eventType: "LINK_CLICK", linkId: { not: null } },
      _count: { linkId: true },
      orderBy: { _count: { linkId: "desc" } },
      take: 5,
    }),
    // Daily views for chart (last 14 days)
    prisma.$queryRaw<{ date: string; count: number }[]>`
      SELECT DATE(created_at) as date, COUNT(*) as count
      FROM analytics_events
      WHERE profile_id = ${profile.id}
        AND event_type = 'PROFILE_VIEW'
        AND created_at >= ${thirtyDaysAgo}
      GROUP BY DATE(created_at)
      ORDER BY date ASC
    `,
  ]);

  // Resolve link titles
  const linkIds = topLinks
    .map((t) => t.linkId)
    .filter(Boolean) as string[];
  const links = await prisma.link.findMany({
    where: { id: { in: linkIds } },
    select: { id: true, title: true },
  });

  const topLinksWithTitles = topLinks.map((t) => ({
    title: links.find((l) => l.id === t.linkId)?.title ?? "Unknown",
    clicks: t._count.linkId,
  }));

  const ctr =
    totalViews > 0 ? Math.round((totalClicks / totalViews) * 100 * 10) / 10 : 0;

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Analytics</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Track your profile performance.
        </p>
      </div>
      <AnalyticsDisplay
        totalViews={totalViews}
        totalClicks={totalClicks}
        ctr={ctr}
        topLinks={topLinksWithTitles}
        dailyViews={dailyViews.map((d) => ({
          date: String(d.date).slice(0, 10),
          count: Number(d.count),
        }))}
      />
    </div>
  );
}
