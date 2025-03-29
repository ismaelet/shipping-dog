import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
	plugins: [
		tailwindcss(),
		laravel({
			input: ['resources/css/app.css', 'resources/js/app.ts'],
			refresh: true,
		}),
	],
	server: {
		origin: process.env.VITE_SERVER_ORIGIN || 'http://localhost:5173',
		host: process.env.VITE_SERVER_HOST === 'true',
	},
})
