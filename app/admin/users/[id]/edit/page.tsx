import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { redirect, notFound } from "next/navigation";
import { EditUserForm } from "@/components/admin/EditUserForm";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "Edit User" };

export default async function EditUserPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const session = await auth();
  if (!session?.user || session.user.role !== "ADMIN") redirect("/login");

  const { id } = await params;
  const user = await prisma.user.findUnique({
    where: { id },
    select: { id: true, name: true, email: true, status: true },
  });

  if (!user) notFound();

  return (
    <div className="max-w-xl space-y-6 animate-fade-in">
      <div>
        <h1 className="text-2xl font-bold text-neutral-900">Edit User</h1>
        <p className="text-neutral-500 text-sm mt-1">Update user information.</p>
      </div>
      <EditUserForm user={user} />
    </div>
  );
}
