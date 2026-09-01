import { NextRequest, NextResponse } from "next/server";
import { revalidatePath } from "next/cache";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { profileSchema } from "@/lib/validations";

export async function GET() {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      include: { theme: true, socialLinks: { orderBy: { sortOrder: "asc" } } },
    });

    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });
    return NextResponse.json(profile);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

export async function PATCH(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const body = await req.json();
    const parsed = profileSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const { displayName, username, bio, location, website } = parsed.data;

    // Get current profile
    const current = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true, username: true },
    });

    if (!current) {
      return NextResponse.json({ error: "Profile not found." }, { status: 404 });
    }

    // Check username uniqueness (excluding current profile)
    if (username.toLowerCase() !== current.username) {
      const conflict = await prisma.profile.findFirst({
        where: { username: username.toLowerCase(), NOT: { id: current.id } },
      });
      if (conflict) {
        return NextResponse.json({ error: "Username is already taken." }, { status: 409 });
      }
    }

    const updated = await prisma.profile.update({
      where: { id: current.id },
      data: {
        displayName,
        username: username.toLowerCase(),
        bio: bio || null,
        location: location || null,
        website: website || null,
      },
    });

    revalidatePath("/", "layout");
    return NextResponse.json(updated);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
