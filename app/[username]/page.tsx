import { notFound } from "next/navigation";
import { prisma } from "@/lib/db";
import type { Metadata } from "next";
import { PublicProfileClient } from "./PublicProfileClient";

interface Props {
  params: Promise<{ username: string }>;
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { username } = await params;
  const profile = await getProfile(username);
  if (!profile) return { title: "Profile not found" };

  return {
    title: `${profile.displayName} | MonoLink`,
    description: profile.bio || `${profile.displayName}'s digital profile`,
    openGraph: {
      title: `${profile.displayName} | MonoLink`,
      description: profile.bio || `${profile.displayName}'s digital profile`,
      images: profile.profileImage ? [{ url: profile.profileImage }] : [],
      type: "profile",
    },
    twitter: {
      card: "summary_large_image",
      title: `${profile.displayName} | MonoLink`,
      description: profile.bio || "",
      images: profile.profileImage ? [profile.profileImage] : [],
    },
  };
}

async function getProfile(username: string) {
  return prisma.profile.findUnique({
    where: { username: username.toLowerCase() },
    include: {
      links: {
        where: { isActive: true },
        orderBy: { sortOrder: "asc" },
      },
      socialLinks: {
        orderBy: { sortOrder: "asc" },
      },
      theme: true,
      user: { select: { status: true } },
    },
  });
}

export default async function PublicProfilePage({ params }: Props) {
  const { username } = await params;
  const profile = await getProfile(username);

  if (!profile || profile.user.status === "INACTIVE") {
    notFound();
  }

  // Convert Prisma result to plain object for client
  const profileData = {
    id: profile.id,
    username: profile.username,
    displayName: profile.displayName,
    bio: profile.bio,
    profileImage: profile.profileImage,
    logo: profile.logo,
    location: profile.location,
    website: profile.website,
    links: profile.links.map((l) => ({
      id: l.id,
      title: l.title,
      url: l.url,
      type: l.type,
      icon: l.icon,
      description: l.description,
    })),
    socialLinks: profile.socialLinks.map((s) => ({
      id: s.id,
      platform: s.platform,
      url: s.url,
    })),
    theme: profile.theme ? {
      templateName: profile.theme.templateName,
      backgroundType: profile.theme.backgroundType,
      backgroundValue: profile.theme.backgroundValue,
      primaryColor: profile.theme.primaryColor,
      secondaryColor: profile.theme.secondaryColor,
      textColor: profile.theme.textColor,
      buttonColor: profile.theme.buttonColor,
      buttonTextColor: profile.theme.buttonTextColor,
      buttonStyle: profile.theme.buttonStyle,
      fontFamily: profile.theme.fontFamily,
      fontSize: profile.theme.fontSize,
      fontWeight: profile.theme.fontWeight,
      layout: profile.theme.layout,
    } : null,
  };

  return <PublicProfileClient profile={profileData} />;
}
