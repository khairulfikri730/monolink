# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## 1. Product Overview

### Product Name

**BioLink Platform**

> Nama dapat diganti sesuai branding final produk.

### Product Type

Multi-user / Multi-tenant Bio Link Management Platform

### Product Concept

Platform yang memungkinkan Admin membuat akun untuk individu, tim, bisnis, organisasi, atau brand sehingga setiap pengguna dapat membuat halaman profil digital yang berisi:

* Profile
* Nama
* Deskripsi
* Social media
* Website
* WhatsApp
* Google Maps
* Marketplace
* Portfolio
* Custom links
* Dan berbagai informasi digital lainnya.

Setiap pengguna mendapatkan URL publik unik yang dapat digunakan sebagai link di bio Instagram, TikTok, WhatsApp, Facebook, LinkedIn, kartu nama digital, QR Code, dan media lainnya.

Contoh:

`domain.com/aura`

`domain.com/company-a`

`domain.com/team-marketing`

---

# 2. Problem Statement

Saat ini seseorang atau sebuah bisnis sering memiliki banyak kanal digital:

* Instagram
* TikTok
* WhatsApp
* Website
* Google Maps
* YouTube
* Marketplace
* Portfolio
* Contact

Namun social media bio biasanya hanya menyediakan satu link.

Pengguna membutuhkan satu halaman yang dapat menjadi **digital hub** untuk mengumpulkan seluruh link tersebut.

Selain itu, organisasi yang memiliki banyak tim membutuhkan sistem yang memungkinkan:

> Admin membuat akun → setiap tim mengelola profile mereka sendiri → semua profile berada dalam satu platform.

Solusi yang dibangun adalah platform bio-link multi-user yang mudah dikustomisasi tanpa membutuhkan kemampuan coding.

---

# 3. Product Vision

Menjadi platform sederhana dan fleksibel untuk membuat **digital identity page** yang dapat digunakan oleh individu, bisnis, organisasi, dan berbagai tim dalam satu sistem.

---

# 4. Product Goals

### Primary Goals

1. Memungkinkan Admin mengelola banyak user.
2. Memungkinkan user membuat profile digital sendiri.
3. Memungkinkan user mengelola berbagai link.
4. Memberikan customization tanpa coding.
5. Menghasilkan public profile yang cepat dan responsive.
6. Menyediakan analytics sederhana.
7. Mengutamakan pengalaman pengguna mobile.

### Success Criteria

Produk dianggap berhasil apabila:

* Admin dapat membuat user baru.
* User dapat login.
* User dapat membuat profile.
* User dapat menambahkan link.
* User dapat mengubah tampilan.
* Profile dapat diakses melalui URL publik.
* Public profile terlihat baik di mobile.
* Setiap user hanya dapat mengakses data miliknya.
* Admin dapat mengelola seluruh user.

---

# 5. Target Users

## 5.1 Admin

Contoh:

* Pemilik bisnis
* Agency
* Organisasi
* Perusahaan
* Administrator platform

Kebutuhan:

* Membuat akun
* Mengelola akun
* Melihat status user
* Mengelola profile
* Melihat statistik

---

## 5.2 Team / User

Contoh:

* Marketing team
* Sales team
* Personal brand
* Content creator
* UMKM
* Organisasi
* Event team

Kebutuhan:

* Membuat profile
* Mengatur link
* Custom appearance
* Membagikan profile

---

## 5.3 Visitor

Pengunjung tidak perlu login.

Mereka hanya membuka:

`domain.com/username`

Tujuan:

* Melihat profile
* Membuka link
* Menghubungi user
* Mengunjungi social media
* Melihat lokasi

---

# 6. User Roles & Permissions

| Feature             |      Admin | User | Visitor |
| ------------------- | ---------: | ---: | ------: |
| Login               |          ✅ |    ✅ |       ❌ |
| Create User         |          ✅ |    ❌ |       ❌ |
| Delete User         |          ✅ |    ❌ |       ❌ |
| Disable User        |          ✅ |    ❌ |       ❌ |
| Edit Own Profile    | ❌/Optional |    ✅ |       ❌ |
| Edit Links          | ❌/Optional |    ✅ |       ❌ |
| Customize Theme     | ❌/Optional |    ✅ |       ❌ |
| View Analytics      |   Platform |  Own |       ❌ |
| View Public Profile |          ✅ |    ✅ |       ✅ |

---

# 7. Core User Flow

## Admin Flow

```text
Admin Login
     ↓
Admin Dashboard
     ↓
Create User
     ↓
Input User Information
     ↓
Account Created
     ↓
User receives login credentials
     ↓
User Login
```

---

## User Flow

```text
Login
 ↓
Dashboard
 ↓
Complete Profile
 ↓
Add Links
 ↓
Customize Appearance
 ↓
Preview
 ↓
Publish
 ↓
Share Profile URL
```

---

## Visitor Flow

```text
Click Bio Link
      ↓
Public Profile
      ↓
View Profile
      ↓
Click Link
      ↓
External Website / WhatsApp / Instagram / Maps
```

---

# 8. Functional Requirements

## FR-01 Authentication

System harus menyediakan:

* Login
* Logout
* Password
* Change password
* Reset password
* Session management

### Requirements

* Password harus di-hash.
* User tidak boleh mengakses dashboard tanpa authentication.
* Role harus divalidasi di server.

---

# 9. FR-02 Admin Dashboard

Admin dashboard menyediakan:

### Overview

Menampilkan:

* Total Users
* Active Users
* Inactive Users
* Total Profile Views
* Total Link Clicks

### User Management

Table:

| Name | Username | Email | Status | Created At | Action |
| ---- | -------- | ----- | ------ | ---------- | ------ |

Actions:

* View
* Edit
* Disable
* Enable
* Delete
* Reset Password

---

# 10. FR-03 Create User

Admin dapat membuat akun baru.

Form:

* Full Name
* Email
* Username
* Temporary Password
* Status

Setelah dibuat:

System otomatis membuat:

* User
* Profile
* Default theme
* Default links

---

# 11. FR-04 User Dashboard

User dashboard terdiri dari:

### Navigation

* Overview
* Profile
* Links
* Appearance
* Social Media
* Analytics
* Settings

---

# 12. FR-05 Profile Management

User dapat mengatur:

### Profile Information

* Profile picture
* Logo
* Display name
* Username
* Bio
* Location
* Website

### Example

**Aura Ashel**

Digital Creator & Entrepreneur

Padang, Indonesia

---

# 13. FR-06 Link Management

User dapat:

* Add link
* Edit link
* Delete link
* Activate/deactivate link
* Reorder link

Setiap link memiliki:

* Title
* URL
* Type
* Icon
* Thumbnail
* Description
* Status
* Order

---

# 14. FR-07 Link Types

Platform menyediakan:

### Social

* Instagram
* TikTok
* YouTube
* Facebook
* LinkedIn
* X

### Communication

* WhatsApp
* Email
* Telegram
* Phone

### Location

* Google Maps

### Business

* Website
* Portfolio
* Shopee
* Tokopedia
* Booking
* Product Catalog

### Custom

User bebas memasukkan:

* Title
* URL
* Icon
* Image

---

# 15. FR-08 Drag & Drop

User dapat mengubah urutan link dengan drag & drop.

Example:

```text
Instagram
WhatsApp
Website
TikTok
Google Maps
```

Menjadi:

```text
WhatsApp
Instagram
Google Maps
Website
TikTok
```

Perubahan harus otomatis tersimpan.

---

# 16. FR-09 WhatsApp Integration

User dapat memasukkan nomor WhatsApp.

System menghasilkan:

`https://wa.me/628xxxxxxxxxx`

Optional:

Custom message.

Example:

> Halo, saya tertarik dengan produk Anda.

---

# 17. FR-10 Social Media

User dapat menambahkan social media.

Platform:

* Instagram
* TikTok
* YouTube
* Facebook
* LinkedIn
* X

Social icons ditampilkan pada public profile.

---

# 18. FR-11 Appearance Customization

User dapat mengubah:

### Background

* Solid
* Gradient
* Image

### Button

* Rounded
* Pill
* Square
* Glass
* Outline

### Colors

* Primary
* Secondary
* Text
* Button
* Background

### Typography

* Font
* Size
* Weight

### Layout

* Profile alignment
* Button layout
* Card style

---

# 19. FR-12 Theme Templates

Sediakan beberapa template awal.

### Template 01 — Classic

Simple profile + buttons.

### Template 02 — Minimal

Clean, white-space oriented.

### Template 03 — Modern

Modern cards and subtle effects.

### Template 04 — Business

Professional appearance.

### Template 05 — Creator

More visual and social-media oriented.

User dapat memilih template kemudian melakukan customization.

---

# 20. FR-13 Live Preview

Dashboard menyediakan preview profile secara realtime.

Desktop:

```text
EDITOR             PREVIEW

Profile             ┌──────────┐
Links               │          │
Appearance          │ Profile  │
                    │          │
                    │ Links    │
                    │          │
                    └──────────┘
```

Perubahan pada editor langsung tercermin pada preview.

---

# 21. FR-14 Public Profile

Setiap user memiliki URL:

`domain.com/{username}`

Public profile dapat diakses tanpa login.

### Struktur

```text
Profile Picture

Display Name

Bio

Social Icons

[ Link ]

[ Link ]

[ Link ]

[ Link ]

[ Link ]

Footer
```

---

# 22. FR-15 Responsive Design

Public profile harus mobile-first.

Target:

* iPhone
* Android
* Tablet
* Laptop
* Desktop

Pada desktop, profile tetap memiliki ukuran maksimal sekitar:

**420–500px**

agar tetap terasa seperti mobile bio page.

---

# 23. FR-16 Analytics

System mencatat:

### Profile Views

Berapa kali profile dibuka.

### Link Clicks

Berapa kali setiap link diklik.

### CTR

Perbandingan click terhadap profile views.

### Top Links

Link yang paling banyak diklik.

Dashboard:

```text
Profile Views
12,540

Link Clicks
3,240

CTR
25.8%

Top Link
WhatsApp
```

---

# 24. FR-17 QR Code

User dapat membuat QR Code profile.

QR mengarah ke:

`domain.com/username`

Use case:

* Business card
* Poster
* Banner
* Packaging
* Event
* Offline promotion

---

# 25. FR-18 Share Profile

Button:

* Copy URL
* Share Profile
* QR Code

Jika browser mendukung:

Gunakan Web Share API.

---

# 26. FR-19 Image Upload

User dapat upload:

* Profile picture
* Logo
* Background

Requirements:

* File validation
* Size validation
* Image optimization
* Compression
* Storage external/object storage

---

# 27. FR-20 SEO

Setiap profile memiliki dynamic:

* Page title
* Meta description
* Open Graph image
* Open Graph title
* Open Graph description

Example:

```text
Aura Ashel | Digital Creator
Digital Creator & Entrepreneur
```

---

# 28. FR-21 Username

Username harus:

* Unique
* Case insensitive
* URL-safe
* Tidak menggunakan reserved words.

Reserved:

```text
admin
login
dashboard
api
settings
register
logout
```

---

# 29. FR-22 Security

System wajib menerapkan:

* Password hashing
* Authorization
* Input validation
* XSS protection
* SQL injection prevention
* Secure file upload
* Rate limiting
* Secure headers
* URL validation

Backend tidak boleh mempercayai data dari frontend.

---

# 30. FR-23 URL Validation

Allowed:

```text
https://
http://
mailto:
tel:
```

Block:

```text
javascript:
data:
vbscript:
```

---

# 31. FR-24 Autosave

Perubahan user disimpan otomatis.

Status:

```text
Saving...
Saved
```

Jika gagal:

```text
Unable to save changes.
Please try again.
```

---

# 32. Data Model

## User

```text
id
name
email
password_hash
role
status
created_at
updated_at
```

## Profile

```text
id
user_id
username
display_name
bio
profile_image
logo
location
website
created_at
updated_at
```

## Link

```text
id
profile_id
title
url
type
icon
thumbnail
description
is_active
sort_order
created_at
updated_at
```

## Social Link

```text
id
profile_id
platform
url
sort_order
```

## Theme

```text
id
profile_id
background_type
background_value
primary_color
secondary_color
text_color
button_style
button_radius
font_family
font_size
layout
```

## Analytics Event

```text
id
profile_id
link_id
event_type
created_at
```

---

# 33. Non-Functional Requirements

## Performance

Public profile harus:

* Fast loading
* Optimized images
* Minimal JavaScript
* Lazy loading
* CDN-ready
* Cache-friendly

---

## Scalability

Architecture harus dapat berkembang dari:

```text
10 users
```

menjadi:

```text
1,000 users
10,000 users
100,000+ users
```

tanpa redesign fundamental database architecture.

---

## Security

Data antar-user harus terisolasi.

User A:

Tidak dapat membaca atau mengubah data User B.

---

## Availability

Public profile harus tetap dapat diakses meskipun dashboard sedang digunakan oleh banyak user.

---

# 34. UI/UX Requirements

Design harus:

* Modern
* Clean
* Premium
* Simple
* Professional
* Mobile-first

Gunakan:

* Consistent spacing
* Rounded corners
* Clear typography
* Subtle shadow
* Smooth interaction
* Accessible contrast

Hindari:

* UI terlalu ramai
* Animasi berlebihan
* Button terlalu kecil
* Font terlalu kecil
* Terlalu banyak warna

---

# 35. Accessibility

System harus mendukung:

* Semantic HTML
* Keyboard navigation
* Focus state
* Screen reader compatibility
* Alt text
* Accessible buttons
* Proper color contrast

Target minimal:

**WCAG 2.1 AA**

---

# 36. Recommended Technology

### Frontend

Next.js + TypeScript

### Styling

Tailwind CSS

### UI

shadcn/ui

### Database

PostgreSQL

### ORM

Prisma

### Validation

Zod

### Drag & Drop

dnd-kit

### Icons

Lucide

### Charts

Recharts

### Authentication

Auth.js atau authentication solution equivalent.

---

# 37. High-Level Architecture

```text
                   USER
                    │
                    ↓
              WEB APPLICATION
                    │
          ┌─────────┴─────────┐
          │                   │
     ADMIN DASHBOARD      USER DASHBOARD
          │                   │
          └─────────┬─────────┘
                    ↓
                API / SERVER
                    │
          ┌─────────┴─────────┐
          ↓                   ↓
      PostgreSQL          File Storage
          │
          ↓
      Analytics
```

Public profile:

```text
Visitor
   ↓
domain.com/username
   ↓
Profile Resolver
   ↓
Database
   ↓
Profile + Links + Theme
   ↓
Public Page
```

---

# 38. MVP Scope

Versi pertama wajib memiliki:

### Authentication

* Login
* Logout

### Admin

* Admin dashboard
* Create user
* Edit user
* Delete user
* Disable user

### User

* Profile editor
* Link management
* Drag & drop
* Social media
* WhatsApp
* Google Maps
* Appearance customization

### Public

* `/username`
* Responsive profile
* Social links
* External links

### Analytics

* Profile views
* Link clicks

---

# 39. Post-MVP Features

Fitur berikut tidak wajib pada MVP tetapi disiapkan untuk roadmap:

### Phase 2

* QR Code
* Advanced analytics
* More themes
* Custom fonts
* Link scheduling
* Link click tracking

### Phase 3

* Custom domain
* White-label
* Team management
* Multiple administrators
* Subscription/billing
* Premium themes

### Phase 4

* Lead capture
* Email collection
* Contact form
* Newsletter
* Product catalog
* Booking integration
* API
* Webhooks

---

# 40. Future SaaS Model

Platform dapat dikembangkan menjadi SaaS.

Contoh:

### Free

* 1 profile
* Basic links
* Basic themes

### Pro

* More customization
* Analytics
* QR Code
* Remove platform branding

### Business

* Multiple users
* Team management
* Custom domain
* Advanced analytics
* White-label

---

# 41. Key Metrics

Product metrics:

### Activation

Percentage of users who complete profile.

### Profile Creation Rate

Users who publish their profile.

### Link Creation

Average number of links per profile.

### Profile Views

Total profile visits.

### Link CTR

Percentage of visitors clicking links.

### Retention

Users who return and update their profile.

---

# 42. Acceptance Criteria

## Admin

* [ ] Admin dapat login.
* [ ] Admin dapat membuat user.
* [ ] Admin dapat melihat user.
* [ ] Admin dapat disable user.
* [ ] Admin dapat delete user.
* [ ] Admin tidak dapat melihat password plaintext.

## User

* [ ] User dapat login.
* [ ] User dapat mengubah profile.
* [ ] User dapat upload profile image.
* [ ] User dapat menambahkan link.
* [ ] User dapat edit link.
* [ ] User dapat delete link.
* [ ] User dapat drag & drop link.
* [ ] User dapat mengubah appearance.
* [ ] User dapat melihat analytics.

## Public

* [ ] `/username` dapat dibuka tanpa login.
* [ ] Profile tampil dengan benar.
* [ ] Semua link dapat diklik.
* [ ] WhatsApp bekerja.
* [ ] Google Maps bekerja.
* [ ] Social media bekerja.
* [ ] Responsive pada mobile.
* [ ] Responsive pada tablet.
* [ ] Responsive pada desktop.

## Security

* [ ] User tidak dapat mengakses profile user lain melalui dashboard.
* [ ] User tidak dapat mengakses admin dashboard.
* [ ] Password di-hash.
* [ ] URL divalidasi.
* [ ] File upload divalidasi.

---

# 43. Definition of Done

MVP dianggap selesai apabila:

1. Application dapat dijalankan.
2. Database berjalan.
3. Authentication berjalan.
4. Admin dapat membuat user.
5. User dapat login.
6. User dapat membuat profile.
7. User dapat menambahkan dan mengatur link.
8. User dapat mengubah appearance.
9. Public profile dapat diakses.
10. Analytics berjalan.
11. Responsive mobile telah diuji.
12. Authorization telah diuji.
13. Tidak ada critical security issue.
14. Tidak ada critical error pada production build.

---

# 44. Product Principle

Prinsip utama produk:

> **Simple to create. Easy to customize. Powerful to share.**

User tidak perlu memahami coding untuk memiliki halaman digital yang terlihat profesional.

Admin tidak perlu membuat website baru untuk setiap tim.

Satu platform dapat digunakan oleh banyak user dengan profile, link, theme, dan analytics yang terpisah.

---

# 45. MVP Product Flow

```text
                 ADMIN
                   │
                   ↓
              Create User
                   │
                   ↓
              User Account
                   │
                   ↓
                 LOGIN
                   │
                   ↓
           Complete Profile
                   │
                   ↓
              Add Links
                   │
                   ↓
         Customize Appearance
                   │
                   ↓
              Live Preview
                   │
                   ↓
               PUBLISH
                   │
                   ↓
        domain.com/username
                   │
                   ↓
                VISITOR
                   │
         ┌─────────┼─────────┐
         ↓         ↓         ↓
      Instagram WhatsApp   Website
```

# 46. Final Product Definition

Produk ini adalah:

**A multi-user digital profile and bio-link platform that allows administrators to create accounts for teams or users, while each user can independently manage their profile, links, social media, appearance, and analytics through a simple dashboard.**

Fokus MVP:

**Admin Management → User Customization → Public Bio Profile → Analytics**

Semua fitur harus dibangun dengan pendekatan **mobile-first, secure, scalable, responsive, modular, dan production-ready**.
