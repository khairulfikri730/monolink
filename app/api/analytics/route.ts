import { NextRequest, NextResponse } from "next/server";
import { prisma } from "@/lib/db";

// POST /api/analytics — record an event (public, no auth required)
export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const { profileId, linkId, eventType } = body;

    if (!profileId || !["PROFILE_VIEW", "LINK_CLICK"].includes(eventType)) {
      return NextResponse.json({ error: "Invalid event." }, { status: 400 });
    }

    // Verify profile exists
    const profile = await prisma.profile.findUnique({
      where: { id: profileId },
      select: { id: true },
    });
    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    // If link_click, verify link belongs to profile
    if (eventType === "LINK_CLICK" && linkId) {
      const link = await prisma.link.findFirst({
        where: { id: linkId, profileId },
        select: { id: true },
      });
      if (!link) return NextResponse.json({ error: "Invalid link." }, { status: 400 });
    }

    await prisma.analyticsEvent.create({
      data: {
        profileId,
        linkId: linkId || null,
        eventType,
      },
    });

    return NextResponse.json({ ok: true });
  } catch {
    return NextResponse.json({ error: "Internal server error." }, { status: 500 });
  }
}
