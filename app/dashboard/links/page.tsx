import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { LinksManager } from "@/components/dashboard/LinksManager";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Links" };

export default async function LinksPage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    select: { id: true },
  });

  if (!profile) redirect("/login");

  const links = await prisma.link.findMany({
    where: { profileId: profile.id },
    orderBy: { sortOrder: "asc" },
  });

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Links</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Add, edit, and reorder your links. Drag to reorder.
        </p>
      </div>
      <LinksManager initialLinks={links} />
    </div>
  );
}
