import { NextRequest, NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { socialLinkSchema } from "@/lib/validations";

export async function GET() {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const social = await prisma.socialLink.findMany({
      where: { profileId: profile.id },
      orderBy: { sortOrder: "asc" },
    });
    return NextResponse.json(social);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const body = await req.json();
    const parsed = socialLinkSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ error: "Validation failed.", issues: parsed.error.flatten().fieldErrors }, { status: 422 });
    }

    // Upsert: one record per platform per profile
    const existing = await prisma.socialLink.findFirst({
      where: { profileId: profile.id, platform: parsed.data.platform },
    });

    if (existing) {
      const updated = await prisma.socialLink.update({
        where: { id: existing.id },
        data: { url: parsed.data.url },
      });
      return NextResponse.json(updated);
    }

    const maxOrder = await prisma.socialLink.aggregate({
      where: { profileId: profile.id },
      _max: { sortOrder: true },
    });

    const link = await prisma.socialLink.create({
      data: {
        profileId: profile.id,
        platform: parsed.data.platform,
        url: parsed.data.url,
        sortOrder: (maxOrder._max.sortOrder ?? -1) + 1,
      },
    });
    return NextResponse.json(link, { status: 201 });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

export async function DELETE(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const { id } = await req.json();
    const link = await prisma.socialLink.findFirst({
      where: { id, profileId: profile.id },
    });
    if (!link) return NextResponse.json({ error: "Not found." }, { status: 404 });

    await prisma.socialLink.delete({ where: { id } });
    return NextResponse.json({ message: "Deleted." });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
