import { NextRequest, NextResponse } from "next/server";
import { revalidatePath } from "next/cache";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import { linkSchema, reorderLinksSchema } from "@/lib/validations";

// GET all links for current user
export async function GET() {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const links = await prisma.link.findMany({
      where: { profileId: profile.id },
      orderBy: { sortOrder: "asc" },
    });

    return NextResponse.json(links);
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// POST create a new link
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
    const parsed = linkSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json(
        { error: "Validation failed.", issues: parsed.error.flatten().fieldErrors },
        { status: 422 }
      );
    }

    // Get max sort order
    const maxOrder = await prisma.link.aggregate({
      where: { profileId: profile.id },
      _max: { sortOrder: true },
    });
    const sortOrder = (maxOrder._max.sortOrder ?? -1) + 1;

    const link = await prisma.link.create({
      data: {
        profileId: profile.id,
        title: parsed.data.title,
        url: parsed.data.url,
        type: parsed.data.type as never,
        icon: parsed.data.icon || null,
        description: parsed.data.description || null,
        isActive: parsed.data.isActive,
        sortOrder,
      },
    });

    revalidatePath("/", "layout");
    return NextResponse.json(link, { status: 201 });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}

// PATCH reorder links
export async function PUT(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    const body = await req.json();
    const parsed = reorderLinksSchema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ error: "Invalid data." }, { status: 422 });
    }

    // Verify all links belong to this profile
    const linkIds = parsed.data.linkIds;
    const ownedLinks = await prisma.link.findMany({
      where: { profileId: profile.id, id: { in: linkIds } },
      select: { id: true },
    });

    if (ownedLinks.length !== linkIds.length) {
      return NextResponse.json({ error: "Invalid link IDs." }, { status: 403 });
    }

    // Update sort orders
    await prisma.$transaction(
      linkIds.map((id, index) =>
        prisma.link.update({
          where: { id },
          data: { sortOrder: index },
        })
      )
    );

    return NextResponse.json({ message: "Links reordered." });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
