import { NextRequest, NextResponse } from "next/server";
import { revalidatePath } from "next/cache";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { themeSchema } from "@/lib/validations";

export async function GET() {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const theme = await prisma.theme.findUnique({ where: { profileId: profile.id } });
    return NextResponse.json(theme);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

export async function PATCH(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const body = await req.json();
    const parsed = themeSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const theme = await prisma.theme.upsert({
      where: { profileId: profile.id },
      create: { profileId: profile.id, ...parsed.data as never },
      update: parsed.data as never,
    });

    revalidatePath("/", "layout");

    return NextResponse.json(theme);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
