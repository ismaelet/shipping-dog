import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/css/main.css', 'resources/css/filament/admin/theme.css', 'resources/js/main.ts'],
			refresh: true,
		}),
	],
})
