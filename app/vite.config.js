import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/css/app.css', 'resources/js/app.js'],
			refresh: ['app/Livewire/**', 'app/View/Components/**', 'lang/**', 'resources/lang/**', 'resources/views/**', 'routes/**', 'app/Filament/**'],
		}),
		tailwindcss(),
	],
	server: {
		host: '0.0.0.0',
		port: 5173,
		hmr: {
			host: 'localhost',
		},
	},
})
