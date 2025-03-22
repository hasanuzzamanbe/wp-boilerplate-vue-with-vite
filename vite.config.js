import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload';
//import copy from 'rollup-plugin-copy'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: 
  [
    vue(),
    liveReload(`${__dirname}/**/*\.php`),
    //copy({
    //  targets: [
    //    { src: 'src/assets/*', dest: 'assets/' },
    //  ]
    //})
  ],

  build: {
    manifest: 'manifest.json',
    outDir: 'assets',
    assetsDir: '',
    publicDir: 'public',
    emptyOutDir: true, // delete the contents of the output directory before each build

 // https://rollupjs.org/guide/en/#big-list-of-options
    rollupOptions: {
      input: [
        'src/admin/start.js',
        // 'src/style.scss',
        // 'src/assets'
      ],
      output: {
        chunkFileNames: 'js/[name].js',
        entryFileNames: 'js/[name].js',
        
        assetFileNames: ({ name }) => {
          if (/\.css$/.test(name ?? '')) return 'css/[name][extname]'
          return '[name][extname]'
        },
      },
    },
  },

  resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm-bundler.js',
    },
  },

  server: {
    host: "0.0.0.0", // DDEV SUPPORT
    port: 5173,
    strictPort: true,
    origin: `${process.env.DDEV_PRIMARY_URL.replace(/:\d+$/, "")}:5173`, // DDEV SUPPORT
    cors: {
      origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(\.ddev\.site)(?::\d+)?$/,
    },
  }
})

