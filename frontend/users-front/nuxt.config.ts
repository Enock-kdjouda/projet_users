// https://nuxt.com/docs/api/configuration/nuxt-config
import { resolve } from 'path';
import tailwindcss from "@tailwindcss/vite";
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },
  modules: [ '@pinia/nuxt'],

 
  css:[
      '@/assets/main.css',
    ],
    alias: {
    "@": resolve(__dirname, './'),
  },
  
    postcss: {
      plugins: {
        tailwindcss: {},
        autoprefixer: {},
      },
    },
    runtimeConfig: {
    public: {
     apiBaseUrl: process.env.API_BASE_URL || 'http://localhost:8000/api',
  }
}

})
