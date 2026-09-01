import { NextRequest, NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { editUserSchema, resetPasswordSchema } from "@/lib/validations";

type Params = { params: Promise<{ id: string }> };

// GET single user
export async function GET(_req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const { id } = await params;
    const user = await prisma.user.findUnique({
      where: { id },
      select: {
        id: true,
        name: true,
        email: true,
        status: true,
        createdAt: true,
        profile: { select: { username: true } },
      },
    });

    if (!user || user === null) {
      return NextResponse.json({ error: "User not found." }, { status: 404 });
    }

    return NextResponse.json(user);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// PATCH edit user
export async function PATCH(req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const { id } = await params;
    const body = await req.json();
    const parsed = editUserSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const { name, email, status } = parsed.data;

    // Check email uniqueness (excluding current user)
    const conflict = await prisma.user.findFirst({
      where: { email: email.toLowerCase(), NOT: { id } },
    });
    if (conflict) {
      return NextResponse.json({ error: "Email is already taken." }, { status: 409 });
    }

    const updated = await prisma.user.update({
      where: { id },
      data: { name, email: email.toLowerCase(), status: status as "ACTIVE" | "INACTIVE" },
    });

    return NextResponse.json({ message: "User updated.", userId: updated.id });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// DELETE user
export async function DELETE(_req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const { id } = await params;

    // Prevent deleting oneself
    if (id === session.user.id) {
      return NextResponse.json({ error: "You cannot delete your own account." }, { status: 400 });
    }

    await prisma.user.delete({ where: { id } });
    return NextResponse.json({ message: "User deleted." });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// POST /api/admin/users/[id]/reset-password  (handled below via sub-route)
export async function PUT(req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const { id } = await params;
    const body = await req.json();
    const parsed = resetPasswordSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const hashed = await bcrypt.hash(parsed.data.newPassword, 12);
    await prisma.user.update({ where: { id }, data: { password: hashed } });

    return NextResponse.json({ message: "Password reset successfully." });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
