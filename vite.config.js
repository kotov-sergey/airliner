import { defineConfig } from 'vite';
import liveReload from 'vite-plugin-live-reload';
import path from 'path';

export default defineConfig({
  plugins: [
    // Этот плагин будет обновлять страницу при изменении PHP файлов
    liveReload([__dirname + '/**/*.php']),
  ],
  base: process.env.NODE_ENV === 'development' ? '/' : '/wp-content/themes/airliner/dist/',
  build: {
    // Вывод файлов в папку dist
    outDir: path.resolve(__dirname, 'dist'),
    emptyOutDir: true,
    manifest: true, // Генерирует manifest.json для связи с PHP
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'), // Твоя точка входа
      },
    },
  },
  server: {
    // Настройки для корректной работы внутри локального сервера (OpenServer, XAMPP, Docker)
    cors: true,
    strictPort: true,
    port: 5173,
    hmr: {
      host: 'localhost',
    },
  },
});