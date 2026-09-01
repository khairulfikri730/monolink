import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { ProfileEditor } from "@/components/dashboard/ProfileEditor";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Profile" };

export default async function ProfilePage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
  });

  if (!profile) redirect("/login");

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Profile</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Manage your public profile information.
        </p>
      </div>
      <ProfileEditor profile={profile} />
    </div>
  );
}
