import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { SocialLinksEditor } from "@/components/dashboard/SocialLinksEditor";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Social Media" };

export default async function SocialPage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    include: { socialLinks: { orderBy: { sortOrder: "asc" } } },
  });

  if (!profile) redirect("/login");

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Social Media</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Add your social media profiles. These appear as icons on your public profile.
        </p>
      </div>
      <SocialLinksEditor profileId={profile.id} socialLinks={profile.socialLinks} />
    </div>
  );
}
