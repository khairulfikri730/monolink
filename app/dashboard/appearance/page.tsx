import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { AppearanceEditor } from "@/components/dashboard/AppearanceEditor";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Appearance" };

export default async function AppearancePage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    include: { theme: true, links: { where: { isActive: true }, take: 3, orderBy: { sortOrder: "asc" } } },
  });

  if (!profile) redirect("/login");

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Appearance</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Customize the look and feel of your public profile.
        </p>
      </div>
      <AppearanceEditor theme={profile.theme} />
    </div>
  );
}
