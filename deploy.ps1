# ==============================================================
# deploy.ps1 — Deploy ke branch production (build artifacts only)
# Jalankan dari root project: .\deploy.ps1
# ==============================================================

$ErrorActionPreference = "Stop"

# ── Warna helper ──────────────────────────────────────────────
function Log-Info  { param($msg) Write-Host "[INFO]  $msg" -ForegroundColor Cyan }
function Log-OK    { param($msg) Write-Host "[OK]    $msg" -ForegroundColor Green }
function Log-Error { param($msg) Write-Host "[ERROR] $msg" -ForegroundColor Red }
function Log-Warn  { param($msg) Write-Host "[WARN]  $msg" -ForegroundColor Yellow }

# ── Pastikan berada di root repo ──────────────────────────────
$rootDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $rootDir

# ── Cek git status bersih ─────────────────────────────────────
Log-Info "Mengecek working tree..."
$status = git status --porcelain | Where-Object { $_ -notmatch "^\?\?" }
if ($status) {
    Log-Error "Ada perubahan yang belum di-commit. Commit atau stash terlebih dahulu."
    git status --short
    exit 1
}
Log-OK "Working tree bersih."

# ── Pastikan di branch main ───────────────────────────────────
$branch = git rev-parse --abbrev-ref HEAD
if ($branch -ne "main") {
    Log-Warn "Saat ini di branch '$branch', berpindah ke main..."
    git checkout main
}
Log-OK "Branch: main"

# ── Pull latest main ──────────────────────────────────────────
Log-Info "Pull latest dari origin/main..."
git pull origin main
Log-OK "main sudah up-to-date."

# ── Build Next.js ─────────────────────────────────────────────
Log-Info "Menjalankan prisma generate..."
npx prisma generate
if ($LASTEXITCODE -ne 0) { Log-Error "prisma generate gagal!"; exit 1 }

Log-Info "Menjalankan npm run build (webpack)..."
npx next build --webpack
if ($LASTEXITCODE -ne 0) {
    Log-Error "Build gagal! Perbaiki error terlebih dahulu."
    exit 1
}
Log-OK "Build berhasil."

# ── Simpan commit hash main untuk pesan commit ────────────────
$mainCommit = git rev-parse --short HEAD

# ── Simpan artifact ke folder sementara ───────────────────────
$tmpDir = "$env:TEMP\monolink-deploy-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
New-Item -ItemType Directory -Path $tmpDir | Out-Null
Log-Info "Menyalin artifact ke: $tmpDir"

# File & folder yang diperlukan server untuk `next start`
$items = @(".next", "public", "package.json", "package-lock.json", "next.config.ts", "next.config.js", "next.config.mjs", "prisma")
foreach ($item in $items) {
    if (Test-Path "$rootDir\$item") {
        Copy-Item -Path "$rootDir\$item" -Destination "$tmpDir\$item" -Recurse -Force
        Log-OK "Disalin: $item"
    }
}

# .gitignore khusus production (izinkan .next, abaikan source)
$prodGitignore = @"
# Production branch — hanya berisi build artifacts
node_modules/
.env*
*.tsbuildinfo
src/
app/
components/
lib/
types/
coverage/
"@
Set-Content -Path "$tmpDir\.gitignore" -Value $prodGitignore

# README singkat
$readmeContent = "# Monolink - Production Build`n`nBranch ini hanya berisi hasil build. Jangan edit langsung.`n`n## Deploy ke server`n`ngit pull origin production`nnpm install --omit=dev`nnpx prisma generate`nnpm start`n`nSource code ada di branch main.`nBuild dari main commit: $mainCommit"
Set-Content -Path "$tmpDir\README.md" -Value $readmeContent

Log-OK "Artifact siap di $tmpDir"

# ── Pindah ke branch production ───────────────────────────────
Log-Info "Berpindah ke branch production..."
git checkout production

# ── Hapus semua file lama di production ───────────────────────
Log-Info "Membersihkan branch production..."
Get-ChildItem -Path $rootDir -Force | Where-Object {
    $_.Name -notin @(".git")
} | Remove-Item -Recurse -Force

# ── Salin artifact dari tmp ke production ─────────────────────
Log-Info "Menyalin artifact ke branch production..."
Get-ChildItem -Path $tmpDir -Force | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination "$rootDir\$($_.Name)" -Recurse -Force
}
Log-OK "Artifact tersalin."

# Bersihkan tmp
Remove-Item -Path $tmpDir -Recurse -Force

# ── Git add & commit ──────────────────────────────────────────
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm"
$commitMsg = "deploy: build from main@$mainCommit [$timestamp]"

Log-Info "Commit: $commitMsg"
git add -A
git status
git commit -m $commitMsg

# ── Push ke remote ────────────────────────────────────────────
Log-Info "Push ke origin/production..."
git push origin production
Log-OK "Push berhasil!"

# ── Kembali ke main ───────────────────────────────────────────
Log-Info "Kembali ke branch main..."
git checkout main
Log-OK "Selesai! Branch production sekarang hanya berisi build artifacts."
Write-Host ""
Write-Host "=== DEPLOY SELESAI ===" -ForegroundColor Green
Write-Host "Server tinggal jalankan: git pull origin production && npm install --omit=dev && npm start" -ForegroundColor Yellow
