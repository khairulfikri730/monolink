"use client";

import { useState } from "react";
import { signIn } from "next-auth/react";
import { useRouter, useSearchParams } from "next/navigation";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { loginSchema, type LoginSchema } from "@/lib/validations";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Eye, EyeOff, Link2, Loader2 } from "lucide-react";

export default function LoginPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const callbackUrl = searchParams.get("callbackUrl") || "/dashboard";

  const [showPassword, setShowPassword] = useState(false);
  const [serverError, setServerError] = useState("");
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginSchema>({
    resolver: zodResolver(loginSchema),
  });

  const onSubmit = async (data: LoginSchema) => {
    setLoading(true);
    setServerError("");
    try {
      const result = await signIn("credentials", {
        email: data.email,
        password: data.password,
        redirect: false,
      });

      if (result?.error) {
        setServerError("Invalid email or password. Please try again.");
        return;
      }

      router.push(callbackUrl);
      router.refresh();
    } catch {
      setServerError("Something went wrong. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex">
      {/* Left Panel — Branding */}
      <div
        className="hidden lg:flex flex-col justify-between p-12 w-1/2"
        style={{
          background:
            "linear-gradient(135deg, #4d52e8 0%, #6172f3 50%, #8098f8 100%)",
        }}
      >
        <div className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
            <Link2 className="w-5 h-5 text-white" />
          </div>
          <span className="text-white font-bold text-xl tracking-tight">
            MonoLink
          </span>
        </div>

        <div>
          <blockquote className="text-white/90 text-2xl font-medium leading-relaxed mb-6">
            "One link to share everything about you — your digital hub."
          </blockquote>
          <div className="flex gap-3">
            {["Profile", "Links", "Analytics", "QR Code"].map((t) => (
              <span
                key={t}
                className="text-xs text-white/70 border border-white/20 rounded-full px-3 py-1"
              >
                {t}
              </span>
            ))}
          </div>
        </div>

        <div className="flex flex-col gap-4">
          {[
            {
              avatar: "A",
              name: "Aura Ashel",
              desc: "Digital Creator · 12.5K views",
              color: "#a4bcfd",
            },
            {
              avatar: "T",
              name: "Team Marketing",
              desc: "Company · 8.2K views",
              color: "#8098f8",
            },
          ].map((item) => (
            <div
              key={item.name}
              className="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl p-3"
            >
              <div
                className="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                style={{ background: item.color }}
              >
                {item.avatar}
              </div>
              <div>
                <p className="text-white text-sm font-medium">{item.name}</p>
                <p className="text-white/60 text-xs">{item.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Right Panel — Login Form */}
      <div className="flex-1 flex flex-col items-center justify-center p-6 lg:p-16 bg-neutral-50">
        {/* Mobile logo */}
        <div className="flex items-center gap-2 mb-10 lg:hidden">
          <div className="w-8 h-8 rounded-xl bg-brand-600 flex items-center justify-center">
            <Link2 className="w-4 h-4 text-white" />
          </div>
          <span className="font-bold text-lg text-neutral-900">MonoLink</span>
        </div>

        <div className="w-full max-w-sm">
          <div className="mb-8">
            <h1 className="text-2xl font-bold text-neutral-900 mb-2">
              Welcome back
            </h1>
            <p className="text-neutral-500 text-sm">
              Sign in to your account to continue.
            </p>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-5" noValidate>
            {/* Server error */}
            {serverError && (
              <div
                role="alert"
                className="text-sm text-destructive bg-red-50 border border-red-100 rounded-lg px-4 py-3 animate-fade-in"
              >
                {serverError}
              </div>
            )}

            {/* Email */}
            <div className="space-y-1.5">
              <Label htmlFor="email">Email address</Label>
              <Input
                id="email"
                type="email"
                placeholder="you@example.com"
                autoComplete="email"
                {...register("email")}
                aria-invalid={!!errors.email}
              />
              {errors.email && (
                <p className="text-xs text-destructive">{errors.email.message}</p>
              )}
            </div>

            {/* Password */}
            <div className="space-y-1.5">
              <Label htmlFor="password">Password</Label>
              <div className="relative">
                <Input
                  id="password"
                  type={showPassword ? "text" : "password"}
                  placeholder="••••••••"
                  autoComplete="current-password"
                  className="pr-10"
                  {...register("password")}
                  aria-invalid={!!errors.password}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword((p) => !p)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600 transition-colors"
                  aria-label={showPassword ? "Hide password" : "Show password"}
                >
                  {showPassword ? (
                    <EyeOff className="w-4 h-4" />
                  ) : (
                    <Eye className="w-4 h-4" />
                  )}
                </button>
              </div>
              {errors.password && (
                <p className="text-xs text-destructive">
                  {errors.password.message}
                </p>
              )}
            </div>

            {/* Submit */}
            <Button
              id="login-btn"
              type="submit"
              className="w-full"
              disabled={loading}
              style={{ background: "var(--primary)" }}
            >
              {loading ? (
                <>
                  <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                  Signing in…
                </>
              ) : (
                "Sign in"
              )}
            </Button>
          </form>

          <p className="text-center text-xs text-neutral-400 mt-8">
            Don&apos;t have an account? Contact your administrator.
          </p>
        </div>
      </div>
    </div>
  );
}
