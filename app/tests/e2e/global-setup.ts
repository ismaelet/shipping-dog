import { randomBytes } from "node:crypto";
import { artisan, E2E_EMAIL } from "./helpers";

// Creates a dedicated user so real accounts are never touched.
export default function globalSetup(): void {
  const password = randomBytes(12).toString("hex");

  process.env.E2E_PASSWORD = password;

  artisan(
    `\\App\\Models\\User::where('email', '${E2E_EMAIL}')->forceDelete();` +
      `\\Illuminate\\Support\\Facades\\DB::table('users')->insert(['email' => '${E2E_EMAIL}', 'password' => \\Illuminate\\Support\\Facades\\Hash::make(getenv('E2E_PASSWORD'))]);`,
    { E2E_PASSWORD: password },
  );
}
