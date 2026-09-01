import { NextRequest, NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { createUserSchema } from "@/lib/validations";

export async function GET() {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const users = await prisma.user.findMany({
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

    return NextResponse.json(users);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user || session.user.role !== "ADMIN") {
      return NextResponse.json({ error: "Forbidden." }, { status: 403 });
    }

    const body = await req.json();
    const parsed = createUserSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const { name, email, username, password, status } = parsed.data;

    // Check for existing email
    const existingEmail = await prisma.user.findUnique({
      where: { email: email.toLowerCase() },
    });
    if (existingEmail) {
      return NextResponse.json({ error: "Email is already taken." }, { status: 409 });
    }

    // Check for existing username
    const existingUsername = await prisma.profile.findUnique({
      where: { username: username.toLowerCase() },
    });
    if (existingUsername) {
      return NextResponse.json({ error: "Username is already taken." }, { status: 409 });
    }

    const hashedPassword = await bcrypt.hash(password, 12);

    // Create user + profile + default theme in a transaction
    const user = await prisma.$transaction(async (tx) => {
      const newUser = await tx.user.create({
        data: {
          name,
          email: email.toLowerCase(),
          password: hashedPassword,
          role: "USER",
          status: status as "ACTIVE" | "INACTIVE",
        },
      });

      const profile = await tx.profile.create({
        data: {
          userId: newUser.id,
          username: username.toLowerCase(),
          displayName: name,
        },
      });

      await tx.theme.create({
        data: {
          profileId: profile.id,
        },
      });

      return newUser;
    });

    return NextResponse.json(
      { message: "User created successfully.", userId: user.id },
      { status: 201 }
    );
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
