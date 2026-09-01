import Link from "next/link";

export default function NotFound() {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-neutral-50 px-4">
      <div className="text-center max-w-sm">
        <p className="text-6xl font-bold text-brand-500 mb-4">404</p>
        <h1 className="text-xl font-bold text-neutral-900 mb-2">Profile not found</h1>
        <p className="text-neutral-500 text-sm mb-8">
          This profile doesn't exist or may have been removed.
        </p>
        <Link
          href="/"
          className="inline-flex items-center justify-center px-6 py-2.5 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors"
        >
          Go to MonoLink
        </Link>
      </div>
    </div>
  );
}
