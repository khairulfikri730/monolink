import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { SettingsClient } from "@/components/dashboard/SettingsClient";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Settings" };

export default async function SettingsPage() {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const user = await prisma.user.findUnique({
    where: { id: session.user.id },
    select: { name: true, email: true },
  });
  const profile = await prisma.profile.findUnique({
    where: { userId: session.user.id },
    select: { username: true },
  });

  if (!user || !profile) redirect("/login");

  const profileUrl = `${process.env.NEXT_PUBLIC_APP_URL || "http://localhost:3000"}/${profile.username}`;

  return (
    <div className="space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Settings</h1>
        <p className="text-neutral-500 text-sm mt-1">
          Manage your account and share your profile.
        </p>
      </div>
      <SettingsClient
        profileUrl={profileUrl}
        username={profile.username}
        userName={user.name}
        userEmail={user.email}
        userId={session.user.id}
      />
    </div>
  );
}
