const { PrismaClient } = require("../lib/generated/prisma");
const bcrypt = require("bcryptjs");
require("dotenv/config");

const prisma = new PrismaClient();

async function main() {
  console.log("🌱 Seeding database…");

  const adminPassword = await bcrypt.hash("admin123456", 12);
  const admin = await prisma.user.upsert({
    where: { email: "admin@monolink.com" },
    update: {},
    create: {
      name: "Administrator",
      email: "admin@monolink.com",
      password: adminPassword,
      role: "ADMIN",
      status: "ACTIVE",
    },
  });
  console.log(`✅ Admin user: ${admin.email}`);

  const demoPassword = await bcrypt.hash("demo123456", 12);
  const demoUser = await prisma.user.upsert({
    where: { email: "demo@monolink.com" },
    update: {},
    create: {
      name: "Aura Ashel",
      email: "demo@monolink.com",
      password: demoPassword,
      role: "USER",
      status: "ACTIVE",
    },
  });

  const existingProfile = await prisma.profile.findUnique({
    where: { userId: demoUser.id },
  });

  if (!existingProfile) {
    const profile = await prisma.profile.create({
      data: {
        userId: demoUser.id,
        username: "aura",
        displayName: "Aura Ashel",
        bio: "Digital Creator & Entrepreneur 🚀",
        location: "Padang, Indonesia",
        website: "https://auraashel.com",
      },
    });

    await prisma.theme.create({
      data: {
        profileId: profile.id,
        templateName: "modern",
        backgroundType: "SOLID",
        backgroundValue: "#0f172a",
        primaryColor: "#6172f3",
        secondaryColor: "#8098f8",
        textColor: "#f1f5f9",
        buttonColor: "#6172f3",
        buttonTextColor: "#ffffff",
        buttonStyle: "ROUNDED",
        fontFamily: "Inter",
        layout: "center",
      },
    });

    const links = [
      { title: "WhatsApp", url: "https://wa.me/6281234567890", type: "WHATSAPP", sortOrder: 0 },
      { title: "Instagram", url: "https://instagram.com/auraashel", type: "INSTAGRAM", sortOrder: 1 },
      { title: "TikTok", url: "https://tiktok.com/@auraashel", type: "TIKTOK", sortOrder: 2 },
      { title: "Portfolio", url: "https://auraashel.com/portfolio", type: "PORTFOLIO", sortOrder: 3 },
      { title: "Google Maps", url: "https://maps.google.com", type: "GOOGLE_MAPS", sortOrder: 4 },
    ];

    for (const link of links) {
      await prisma.link.create({
        data: { profileId: profile.id, ...link, isActive: true },
      });
    }

    await prisma.socialLink.createMany({
      data: [
        { profileId: profile.id, platform: "INSTAGRAM", url: "https://instagram.com/auraashel", sortOrder: 0 },
        { profileId: profile.id, platform: "TIKTOK", url: "https://tiktok.com/@auraashel", sortOrder: 1 },
        { profileId: profile.id, platform: "YOUTUBE", url: "https://youtube.com/@auraashel", sortOrder: 2 },
      ],
    });
    console.log(`✅ Demo user: ${demoUser.email} → /aura`);
  } else {
    console.log(`ℹ️  Demo profile already exists`);
  }

  console.log("\n🎉 Seed complete!");
  console.log("──────────────────────────────");
  console.log("Admin:     admin@monolink.com / admin123456");
  console.log("Demo User: demo@monolink.com / demo123456");
  console.log("Profile:   http://localhost:3000/aura");
  console.log("──────────────────────────────");
}

main()
  .catch((e) => { console.error("Seed failed:", e); process.exit(1); })
  .finally(async () => { await prisma.$disconnect(); });
