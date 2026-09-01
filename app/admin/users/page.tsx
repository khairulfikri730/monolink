import { prisma } from "@/lib/db";
import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";
import Link from "next/link";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { UserActionsDropdown } from "@/components/admin/UserActionsDropdown";
import { Plus, Users } from "lucide-react";
import type { Metadata } from "next";

export const metadata: Metadata = { title: "User Management" };

async function getUsers() {
  return prisma.user.findMany({
    where: { role: "USER" },
    orderBy: { createdAt: "desc" },
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

export default async function UsersPage() {
  const session = await auth();
  if (!session?.user || session.user.role !== "ADMIN") redirect("/login");

  const users = await getUsers();

  return (
    <div className="space-y-6 animate-fade-in">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-neutral-900">Users</h1>
          <p className="text-neutral-500 text-sm mt-1">
            {users.length} {users.length === 1 ? "user" : "users"} registered.
          </p>
        </div>
        <Button id="create-user-btn" asChild>
          <Link href="/admin/users/new">
            <Plus className="w-4 h-4 mr-2" />
            Create User
          </Link>
        </Button>
      </div>

      {/* Table */}
      <Card className="border-border shadow-sm overflow-hidden">
        {users.length === 0 ? (
          <div className="flex flex-col items-center justify-center py-20 gap-3">
            <div className="w-14 h-14 rounded-2xl bg-neutral-100 flex items-center justify-center">
              <Users className="w-6 h-6 text-neutral-400" />
            </div>
            <p className="text-neutral-600 font-medium">No users yet</p>
            <p className="text-neutral-400 text-sm">
              Create the first user to get started.
            </p>
            <Button id="create-first-user-btn" asChild className="mt-2">
              <Link href="/admin/users/new">
                <Plus className="w-4 h-4 mr-2" />
                Create User
              </Link>
            </Button>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-neutral-50">
                  {["Name", "Username", "Email", "Status", "Created", "Actions"].map(
                    (h) => (
                      <th
                        key={h}
                        className="text-left px-4 py-3 font-medium text-neutral-500 text-xs uppercase tracking-wide whitespace-nowrap"
                      >
                        {h}
                      </th>
                    )
                  )}
                </tr>
              </thead>
              <tbody>
                {users.map((u) => (
                  <tr
                    key={u.id}
                    className="border-b border-border last:border-0 hover:bg-neutral-50 transition-colors"
                  >
                    <td className="px-4 py-3 font-medium text-neutral-800 whitespace-nowrap">
                      {u.name}
                    </td>
                    <td className="px-4 py-3 text-neutral-500">
                      {u.profile?.username ? (
                        <Link
                          href={`/${u.profile.username}`}
                          target="_blank"
                          className="text-brand-600 hover:underline"
                        >
                          @{u.profile.username}
                        </Link>
                      ) : (
                        <span className="text-neutral-300">—</span>
                      )}
                    </td>
                    <td className="px-4 py-3 text-neutral-500">{u.email}</td>
                    <td className="px-4 py-3">
                      <Badge
                        className={
                          u.status === "ACTIVE"
                            ? "bg-green-50 text-green-700 border-green-200"
                            : "bg-neutral-100 text-neutral-500 border-neutral-200"
                        }
                      >
                        {u.status === "ACTIVE" ? "Active" : "Inactive"}
                      </Badge>
                    </td>
                    <td className="px-4 py-3 text-neutral-400 text-xs whitespace-nowrap">
                      {new Date(u.createdAt).toLocaleDateString("id-ID", {
                        day: "2-digit",
                        month: "short",
                        year: "numeric",
                      })}
                    </td>
                    <td className="px-4 py-3">
                      <UserActionsDropdown
                        userId={u.id}
                        userName={u.name}
                        currentStatus={u.status}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </Card>
    </div>
  );
}
