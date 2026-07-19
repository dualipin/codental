import path from 'node:path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import inertia from '@inertiajs/vite'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.ts', 'resources/js/main.ts'],
      refresh: true,
    }),
    tailwindcss(),
      vue({
          template: { transformAssetUrls: { base: null, includeAbsolute: false } },
      }),
    inertia({
        ssr: false,
        pages: 'resources/js/Pages',
    }),
  ],
  server: {
    watch: {
      ignored: ['**/storage/framework/views/**'],
    },
  },
})
