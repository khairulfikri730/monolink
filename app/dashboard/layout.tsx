import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/db";
import { DashboardSidebar } from "@/components/dashboard/DashboardSidebar";
import { DashboardMobileNav } from "@/components/dashboard/DashboardMobileNav";

async function getUserProfile(userId: string) {
  return prisma.profile.findUnique({
    where: { userId },
    select: { username: true },
  });
}

export default async function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const session = await auth();
  if (!session?.user) redirect("/login");

  const profile = await getUserProfile(session.user.id);

  return (
    <div className="flex min-h-screen bg-neutral-50">
      <DashboardSidebar username={profile?.username} />
      <div className="flex-1 flex flex-col min-w-0">
        <DashboardMobileNav username={profile?.username} />
        <main className="flex-1 p-4 lg:p-8 max-w-5xl w-full mx-auto">
          {children}
        </main>
      </div>
    </div>
  );
}
