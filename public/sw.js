const CACHE_NAME = 'sahabatnews-v2'; // Nama cache baru
const OFFLINE_URL = 'offline.html';

// Daftar file yang akan di-cache saat instalasi
const FILES_TO_CACHE = [
    '/',
    '/offline.html',
    '/logosn.svg',
    '/css/404-space.css',
    '/assets/404/404.svg',
    '/assets/404/rocket.svg',
    '/assets/404/earth.svg',
    '/assets/404/moon.svg',
    '/assets/404/astronaut.svg',
    '/assets/404/overlay_stars.svg',
    '/assets/404/bg_purple.png'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Pre-caching offline page');
      return cache.addAll(FILES_TO_CACHE);
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[ServiceWorker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      (async () => {
        try {
          const networkResponse = await fetch(event.request);
          return networkResponse;
        } catch (error) {
          console.log('[ServiceWorker] Fetch failed; returning offline page.', error);
          const cache = await caches.open(CACHE_NAME);
          const cachedResponse = await cache.match(OFFLINE_URL);
          return cachedResponse;
        }
      })()
    );
  }
});
