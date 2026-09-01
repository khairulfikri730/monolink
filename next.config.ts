import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  images: {
    // Allow images from any origin (for uploaded files)
    remotePatterns: [],
    // Allow local uploads
    localPatterns: [
      {
        pathname: "/uploads/**",
        search: "",
      },
    ],
  },
  typescript: {
    ignoreBuildErrors: true,
  },
  // mariadb uses native Node.js bindings — cannot be bundled by webpack
  serverExternalPackages: ["mariadb"],
  // Security headers
  async headers() {
    return [
      {
        source: "/(.*)",
        headers: [
          { key: "X-Content-Type-Options", value: "nosniff" },
          { key: "X-Frame-Options", value: "DENY" },
          { key: "X-XSS-Protection", value: "1; mode=block" },
          { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
        ],
      },
    ];
  },
};

export default nextConfig;
