"use client";

import { Card } from "@/components/ui/card";
import { formatNumber } from "@/lib/utils";
import { Eye, MousePointerClick, TrendingUp, Award } from "lucide-react";
import {
  AreaChart,
  Area,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from "recharts";

interface AnalyticsDisplayProps {
  totalViews: number;
  totalClicks: number;
  ctr: number;
  topLinks: { title: string; clicks: number }[];
  dailyViews: { date: string; count: number }[];
}

export function AnalyticsDisplay({
  totalViews,
  totalClicks,
  ctr,
  topLinks,
  dailyViews,
}: AnalyticsDisplayProps) {
  const stats = [
    {
      label: "Profile Views",
      value: formatNumber(totalViews),
      icon: Eye,
      color: "text-blue-600",
      bg: "bg-blue-50",
      desc: "All time",
    },
    {
      label: "Link Clicks",
      value: formatNumber(totalClicks),
      icon: MousePointerClick,
      color: "text-purple-600",
      bg: "bg-purple-50",
      desc: "All time",
    },
    {
      label: "CTR",
      value: `${ctr}%`,
      icon: TrendingUp,
      color: "text-green-600",
      bg: "bg-green-50",
      desc: "Click-through rate",
    },
  ];

  return (
    <div className="space-y-6">
      {/* Stats */}
      <div className="grid sm:grid-cols-3 gap-4">
        {stats.map((s) => (
          <Card key={s.label} className="p-5 border-border shadow-sm">
            <div className={`w-9 h-9 rounded-xl ${s.bg} flex items-center justify-center mb-3`}>
              <s.icon className={`w-4 h-4 ${s.color}`} />
            </div>
            <p className="text-2xl font-bold text-neutral-900">{s.value}</p>
            <p className="text-xs text-neutral-500 mt-0.5">{s.label}</p>
            <p className="text-[11px] text-neutral-400 mt-0.5">{s.desc}</p>
          </Card>
        ))}
      </div>

      {/* Chart */}
      {dailyViews.length > 0 ? (
        <Card className="p-6 border-border shadow-sm">
          <h2 className="text-sm font-semibold text-neutral-800 mb-5">Profile Views — Last 30 Days</h2>
          <div className="h-48">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={dailyViews} margin={{ top: 0, right: 0, bottom: 0, left: -20 }}>
                <defs>
                  <linearGradient id="viewsGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#6172f3" stopOpacity={0.3} />
                    <stop offset="95%" stopColor="#6172f3" stopOpacity={0} />
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                <XAxis
                  dataKey="date"
                  tick={{ fontSize: 11, fill: "#9ca3af" }}
                  tickFormatter={(d) => {
                    const dt = new Date(d);
                    return `${dt.getMonth() + 1}/${dt.getDate()}`;
                  }}
                />
                <YAxis tick={{ fontSize: 11, fill: "#9ca3af" }} allowDecimals={false} />
                <Tooltip
                  contentStyle={{ fontSize: 12, borderRadius: 8, border: "1px solid #e5e7eb" }}
                  labelFormatter={(d) => new Date(d).toLocaleDateString("id-ID")}
                />
                <Area
                  type="monotone"
                  dataKey="count"
                  stroke="#6172f3"
                  strokeWidth={2}
                  fill="url(#viewsGrad)"
                  name="Views"
                />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </Card>
      ) : (
        <Card className="p-8 border-border shadow-sm text-center">
          <div className="w-12 h-12 rounded-2xl bg-neutral-100 flex items-center justify-center mx-auto mb-3">
            <TrendingUp className="w-5 h-5 text-neutral-400" />
          </div>
          <p className="text-neutral-600 font-medium text-sm">No views yet</p>
          <p className="text-neutral-400 text-xs mt-1">
            Share your profile to start tracking.
          </p>
        </Card>
      )}

      {/* Top Links */}
      <Card className="p-6 border-border shadow-sm">
        <h2 className="text-sm font-semibold text-neutral-800 mb-4 flex items-center gap-2">
          <Award className="w-4 h-4 text-brand-500" />
          Top Links
        </h2>
        {topLinks.length === 0 ? (
          <p className="text-neutral-400 text-sm">No link clicks recorded yet.</p>
        ) : (
          <div className="space-y-3">
            {topLinks.map((link, i) => {
              const max = topLinks[0].clicks;
              const pct = max > 0 ? (link.clicks / max) * 100 : 0;
              return (
                <div key={link.title} className="flex items-center gap-3">
                  <span className="text-xs font-bold text-neutral-400 w-4 flex-shrink-0">
                    {i + 1}
                  </span>
                  <div className="flex-1 min-w-0">
                    <div className="flex justify-between mb-1">
                      <span className="text-sm font-medium text-neutral-700 truncate">{link.title}</span>
                      <span className="text-xs text-neutral-500 ml-2 flex-shrink-0">{formatNumber(link.clicks)}</span>
                    </div>
                    <div className="h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                      <div
                        className="h-full bg-brand-500 rounded-full transition-all"
                        style={{ width: `${pct}%` }}
                      />
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </Card>
    </div>
  );
}
