# Monolink - Production Build

Branch ini hanya berisi hasil build. Jangan edit langsung.

## Deploy ke server

git pull origin production
npm install --omit=dev
npx prisma generate
npm start

Source code ada di branch main.
Build dari main commit: 6064085
