import { NextRequest, NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/db";
import path from "path";
import fs from "fs/promises";
import { v4 as uuidv4 } from "uuid";

const UPLOAD_DIR = path.join(process.cwd(), "public", "uploads");
const MAX_SIZE = 5 * 1024 * 1024; // 5MB
const ALLOWED_TYPES = ["image/jpeg", "image/png", "image/webp", "image/gif"];

async function ensureUploadDir() {
  await fs.mkdir(UPLOAD_DIR, { recursive: true });
}

export async function POST(req: NextRequest) {
  try {
    const session = await auth();
    if (!session?.user) return NextResponse.json({ error: "Unauthorized." }, { status: 401 });

    const formData = await req.formData();
    const file = formData.get("file") as File | null;
    const type = formData.get("type") as string | null; // "profileImage" | "logo" | "background"

    if (!file) return NextResponse.json({ error: "No file provided." }, { status: 400 });
    if (!ALLOWED_TYPES.includes(file.type)) {
      return NextResponse.json({ error: "Invalid file type. Only JPEG, PNG, WebP, and GIF are allowed." }, { status: 400 });
    }
    if (file.size > MAX_SIZE) {
      return NextResponse.json({ error: "File too large. Maximum size is 5MB." }, { status: 400 });
    }

    await ensureUploadDir();

    const ext = file.type.split("/")[1].replace("jpeg", "jpg");
    const filename = `${uuidv4()}.${ext}`;
    const filepath = path.join(UPLOAD_DIR, filename);
    const buffer = Buffer.from(await file.arrayBuffer());
    await fs.writeFile(filepath, buffer);

    const url = `/uploads/${filename}`;

    // Update profile with new image URL
    const profile = await prisma.profile.findUnique({
      where: { userId: session.user.id },
      select: { id: true },
    });

    if (!profile) return NextResponse.json({ error: "Profile not found." }, { status: 404 });

    if (type === "profileImage" || type === "logo") {
      await prisma.profile.update({
        where: { id: profile.id },
        data: { [type]: url },
      });
    } else if (type === "background") {
      await prisma.theme.updateMany({
        where: { profileId: profile.id },
        data: { backgroundType: "IMAGE", backgroundValue: url },
      });
    }

    return NextResponse.json({ url });
  } catch {
    return NextResponse.json({ error: "Upload failed." }, { status: 500 });
  }
}
