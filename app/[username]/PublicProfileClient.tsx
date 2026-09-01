"use client";

import { useEffect, useCallback } from "react";
import Image from "next/image";
import {
  Camera,
  Video,
  Users,
  Briefcase,
  MessageSquare,
  ExternalLink,
  MapPin,
  Globe,
  MessageCircle,
  Phone,
  Mail,
  Send,
} from "lucide-react";

// ─── Types ──────────────────────────────────────────────────────────────────

interface Theme {
  backgroundType: string;
  backgroundValue: string;
  primaryColor: string;
  secondaryColor: string;
  textColor: string;
  buttonColor: string;
  buttonTextColor: string;
  buttonStyle: string;
  fontFamily: string;
  fontSize: string;
  fontWeight: string;
  layout: string;
}

interface LinkData {
  id: string;
  title: string;
  url: string;
  type: string;
  icon: string | null;
  description: string | null;
}

interface SocialLinkData {
  id: string;
  platform: string;
  url: string;
}

interface ProfileData {
  id: string;
  username: string;
  displayName: string;
  bio: string | null;
  profileImage: string | null;
  logo: string | null;
  location: string | null;
  website: string | null;
  links: LinkData[];
  socialLinks: SocialLinkData[];
  theme: Theme | null;
}

// ─── Helpers ─────────────────────────────────────────────────────────────────

const DEFAULT_THEME: Theme = {
  backgroundType: "SOLID",
  backgroundValue: "#f9fafb",
  primaryColor: "#4d52e8",
  secondaryColor: "#6172f3",
  textColor: "#111827",
  buttonColor: "#111827",
  buttonTextColor: "#ffffff",
  buttonStyle: "ROUNDED",
  fontFamily: "Inter",
  layout: "center",
};

function getBackground(theme: Theme): React.CSSProperties {
  if (theme.backgroundType === "GRADIENT") {
    return { background: theme.backgroundValue };
  }
  if (theme.backgroundType === "IMAGE") {
    return {
      backgroundImage: `url(${theme.backgroundValue})`,
      backgroundSize: "cover",
      backgroundPosition: "center",
    };
  }
  return { background: theme.backgroundValue };
}

function getButtonStyle(theme: Theme): React.CSSProperties {
  const base: React.CSSProperties = {
    backgroundColor: theme.buttonColor,
    color: theme.buttonTextColor,
    width: "100%",
    padding: "0.875rem 1.25rem",
    display: "flex",
    alignItems: "center",
    gap: "0.5rem",
    textDecoration: "none",
    fontWeight: 500,
    fontSize: "0.9375rem",
    transition: "transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease",
    cursor: "pointer",
    border: "none",
  };

  switch (theme.buttonStyle) {
    case "PILL":
      return { ...base, borderRadius: "999px" };
    case "SQUARE":
      return { ...base, borderRadius: "0px" };
    case "GLASS":
      return {
        ...base,
        backgroundColor: "rgba(255,255,255,0.15)",
        backdropFilter: "blur(10px)",
        border: "1px solid rgba(255,255,255,0.3)",
        color: theme.buttonTextColor,
        borderRadius: "12px",
      };
    case "OUTLINE":
      return {
        ...base,
        backgroundColor: "transparent",
        border: `2px solid ${theme.buttonColor}`,
        color: theme.buttonColor,
        borderRadius: "10px",
      };
    default: // ROUNDED
      return { ...base, borderRadius: "12px" };
  }
}

function SocialIcon({ platform }: { platform: string }) {
  const size = "w-5 h-5";
  switch (platform) {
    case "INSTAGRAM": return <Camera className={size} />;
    case "YOUTUBE": return <Video className={size} />;
    case "FACEBOOK": return <Users className={size} />;
    case "LINKEDIN": return <Briefcase className={size} />;
    case "X_TWITTER": return <MessageSquare className={size} />;
    case "TIKTOK":
      return (
        <svg className={size} viewBox="0 0 24 24" fill="currentColor">
          <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.99a8.18 8.18 0 004.78 1.52V7.05a4.85 4.85 0 01-1.01-.36z" />
        </svg>
      );
    default: return <Globe className={size} />;
  }
}

function LinkIcon({ type }: { type: string }) {
  const size = "w-4 h-4 flex-shrink-0";
  switch (type) {
    case "WHATSAPP": return <MessageCircle className={size} />;
    case "EMAIL": return <Mail className={size} />;
    case "PHONE": return <Phone className={size} />;
    case "TELEGRAM": return <Send className={size} />;
    case "GOOGLE_MAPS": return <MapPin className={size} />;
    case "INSTAGRAM": return <Camera className={size} />;
    case "YOUTUBE": return <Video className={size} />;
    case "FACEBOOK": return <Users className={size} />;
    case "LINKEDIN": return <Briefcase className={size} />;
    case "X_TWITTER": return <MessageSquare className={size} />;
    case "WEBSITE":
    case "PORTFOLIO": return <Globe className={size} />;
    default: return <ExternalLink className={size} />;
  }
}

// ─── Main Component ───────────────────────────────────────────────────────────

export function PublicProfileClient({ profile }: { profile: ProfileData }) {
  const theme = profile.theme ?? DEFAULT_THEME;
  const isCenter = theme.layout === "center";

  // Track profile view
  useEffect(() => {
    fetch("/api/analytics", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        profileId: profile.id,
        eventType: "PROFILE_VIEW",
      }),
    }).catch(() => {}); // silent fail
  }, [profile.id]);

  const handleLinkClick = useCallback(
    (linkId: string, url: string) => {
      // Track click
      fetch("/api/analytics", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          profileId: profile.id,
          linkId,
          eventType: "LINK_CLICK",
        }),
      }).catch(() => {});
      // Navigate
      window.open(url, "_blank", "noopener,noreferrer");
    },
    [profile.id]
  );

  const btnStyle = getButtonStyle(theme);

  return (
    <div
      className="min-h-screen w-full flex flex-col items-center sm:py-12 relative overflow-hidden"
      style={{
        fontFamily: `'${theme.fontFamily}', Inter, system-ui, sans-serif`,
        color: theme.textColor,
        fontWeight: theme.fontWeight === "bold" ? 700 : theme.fontWeight === "medium" ? 500 : 400,
        fontSize: theme.fontSize === "lg" ? "1.125rem" : theme.fontSize === "sm" ? "0.875rem" : "1rem",
        backgroundColor: "#000",
      }}
    >
      {/* Outer ambient background */}
      <div 
        className="absolute inset-0 z-0 pointer-events-none opacity-80"
        style={{
           ...getBackground(theme),
           filter: "blur(60px) brightness(0.6)",
           transform: "scale(1.2)"
        }}
      />

      {/* Inner phone-like card */}
      <div
        style={{
          ...getBackground(theme),
          maxWidth: "480px",
          width: "100%",
        }}
        className="relative z-10 flex flex-col min-h-[100vh] sm:min-h-[85vh] sm:rounded-[36px] shadow-2xl py-12 px-5 sm:px-8 sm:border sm:border-white/10"
      >
        {/* Profile Header */}
        <div className={`flex flex-col ${isCenter ? "items-center text-center" : "items-start text-left"} gap-3 mb-6`}>
          {/* Avatar */}
          <div
            className="rounded-full overflow-hidden flex-shrink-0"
            style={{ width: 88, height: 88, boxShadow: "0 4px 16px rgba(0,0,0,0.12)" }}
          >
            {profile.profileImage ? (
              <Image
                src={profile.profileImage}
                alt={profile.displayName}
                width={88}
                height={88}
                className="w-full h-full object-cover"
                priority
              />
            ) : (
              <div
                className="w-full h-full flex items-center justify-center text-white text-3xl font-bold"
                style={{ background: `linear-gradient(135deg, ${theme.primaryColor}, ${theme.secondaryColor})` }}
              >
                {profile.displayName?.[0]?.toUpperCase() ?? "?"}
              </div>
            )}
          </div>

          {/* Name & Bio */}
          <div>
            <h1
              className="text-xl font-bold leading-tight"
              style={{ color: theme.textColor }}
            >
              {profile.displayName}
            </h1>
            {profile.bio && (
              <p
                className="mt-1 text-sm leading-relaxed opacity-80"
                style={{ color: theme.textColor }}
              >
                {profile.bio}
              </p>
            )}
            {profile.location && (
              <p
                className="mt-1.5 text-xs opacity-60 flex items-center gap-1"
                style={{ color: theme.textColor, justifyContent: isCenter ? "center" : "flex-start" }}
              >
                <MapPin className="w-3 h-3" />
                {profile.location}
              </p>
            )}
          </div>

          {/* Social Icons */}
          {profile.socialLinks.length > 0 && (
            <div className={`flex gap-3 mt-1 flex-wrap ${isCenter ? "justify-center" : ""}`}>
              {profile.socialLinks.map((sl) => (
                <a
                  key={sl.id}
                  href={sl.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="rounded-full p-2 transition-transform hover:scale-110 active:scale-95"
                  style={{
                    background: "rgba(128,128,128,0.1)",
                    color: theme.textColor,
                  }}
                  aria-label={sl.platform}
                >
                  <SocialIcon platform={sl.platform} />
                </a>
              ))}
            </div>
          )}
        </div>

        {/* Links */}
        <div className="flex flex-col gap-3">
          {profile.links.map((link) => {
            if (link.type === "GOOGLE_MAPS") {
              const mapQuery = encodeURIComponent(link.description || link.title);
              const embedUrl = link.url.includes("/embed") 
                ? link.url 
                : `https://maps.google.com/maps?q=${mapQuery}&t=&z=15&ie=UTF8&iwloc=&output=embed`;

              return (
                <div 
                  key={link.id} 
                  className="w-full overflow-hidden mb-2 transition-transform hover:scale-[1.02]"
                  style={{ 
                    borderRadius: btnStyle.borderRadius, 
                    border: btnStyle.border !== "none" ? btnStyle.border : `1px solid rgba(128,128,128,0.2)`,
                    backgroundColor: theme.backgroundType === "SOLID" ? "rgba(0,0,0,0.05)" : "rgba(255,255,255,0.1)"
                  }}
                >
                  <div className="px-4 py-3 border-b flex items-center gap-2" style={{ borderColor: "rgba(128,128,128,0.15)" }}>
                    <MapPin className="w-4 h-4" style={{ color: theme.textColor }} />
                    <span className="font-semibold text-sm" style={{ color: theme.textColor }}>{link.title}</span>
                  </div>
                  <iframe 
                    width="100%" 
                    height="250" 
                    frameBorder="0" 
                    scrolling="no" 
                    marginHeight={0} 
                    marginWidth={0} 
                    src={embedUrl}
                    className="w-full bg-neutral-100"
                    title={link.title}
                  ></iframe>
                </div>
              );
            }

            return (
              <button
                key={link.id}
                onClick={() => handleLinkClick(link.id, link.url)}
                style={btnStyle}
                className="hover:opacity-90 active:scale-[0.98] hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-offset-2"
                aria-label={`Open ${link.title}`}
              >
                <LinkIcon type={link.type} />
                <span className="flex-1 text-left">{link.title}</span>
                <ExternalLink className="w-3.5 h-3.5 opacity-50 flex-shrink-0" />
              </button>
            );
          })}
        </div>

        {/* Empty state */}
        {profile.links.length === 0 && (
          <div
            className="text-center py-10 text-sm opacity-50"
            style={{ color: theme.textColor }}
          >
            No links yet.
          </div>
        )}

        {/* Footer */}
        <div className="mt-10 text-center">
          <a
            href="https://monodev.tech/"
            className="text-xs opacity-40 hover:opacity-60 transition-opacity"
            style={{ color: theme.textColor }}
          >
            Powered by Monodev
          </a>
        </div>
      </div>
    </div>
  );
}
