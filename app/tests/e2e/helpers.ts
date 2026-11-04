import { execFileSync } from "node:child_process";
import { resolve } from "node:path";

export const E2E_EMAIL = "e2e-user@example.test";

const composeFile = resolve(import.meta.dirname, "../../../compose.yml");

export function artisan(php: string, env: Record<string, string> = {}): void {
  const envArgs = Object.entries(env).flatMap(([key, value]) => ["-e", `${key}=${value}`]);

  execFileSync(
    "docker",
    [
      "compose",
      "-f",
      composeFile,
      "exec",
      "-T",
      ...envArgs,
      "app",
      "php",
      "artisan",
      "tinker",
      `--execute=${php}`,
    ],
    { stdio: "pipe" },
  );
}
