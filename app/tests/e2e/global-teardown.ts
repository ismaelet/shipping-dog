import { artisan, E2E_EMAIL } from "./helpers";

export default function globalTeardown(): void {
  artisan(
    `$u = \\App\\Models\\User::where('email', '${E2E_EMAIL}')->first();` +
      `if ($u) { \\Illuminate\\Support\\Facades\\Storage::disk('public')->delete(['users/icons/'.$u->id.'.webp', 'shops/covers/'.$u->id.'.webp']); $u->forceDelete(); }`,
  );
}
