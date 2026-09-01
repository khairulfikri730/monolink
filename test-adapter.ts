import { PrismaMariadb } from "@prisma/adapter-mariadb";
import mariadb from "mariadb";

async function run() {
  const pool = mariadb.createPool("mysql://root:@localhost:3306/monolink");
  const adapter = new PrismaMariadb(pool);
  console.log("Success");
  process.exit(0);
}
run();
