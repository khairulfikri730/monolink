import { NextRequest, NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { linkSchema } from "@/lib/validations";

type Params = { params: Promise<{ id: string }> };

// Helper: verify link ownership
async function getLinkWithOwnership(linkId: string, userId: string) {
  const profile = await prisma.profile.findUnique({
    where: { userId },
    select: { id: true },
  });
  if (!profile) return null;

  const link = await prisma.link.findFirst({
    where: { id: linkId, profileId: profile.id },
  });
  return link;
}

// PATCH edit link
export async function PATCH(req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const { id } = await params;
    const link = await getLinkWithOwnership(id, session.user.id);
    if (!link) return NextResponse.json({ error: "Link not found." }, { status: 404 });

    const body = await req.json();

    // Handle toggle-only (isActive)
    if (Object.keys(body).length === 1 && "isActive" in body) {
      const updated = await prisma.link.update({
        where: { id },
        data: { isActive: body.isActive },
      });
      return NextResponse.json(updated);
    }

    const parsed = linkSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    const updated = await prisma.link.update({
      where: { id },
      data: {
        title: parsed.data.title,
        url: parsed.data.url,
        type: parsed.data.type as never,
        icon: parsed.data.icon || null,
        description: parsed.data.description || null,
        isActive: parsed.data.isActive,
      },
    });

    return NextResponse.json(updated);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// DELETE link
export async function DELETE(_req: NextRequest, { params }: Params) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const { id } = await params;
    const link = await getLinkWithOwnership(id, session.user.id);
    if (!link) return NextResponse.json({ error: "Link not found." }, { status: 404 });

    await prisma.link.delete({ where: { id } });
    return NextResponse.json({ message: "Link deleted." });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
