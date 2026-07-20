/* Service Worker Dayakarya
 * Strategi:
 * - Navigation: network-first + navigation preload + offline fallback
 * - CSS/JS/font: stale-while-revalidate
 * - Image/icon: cache-first
 */
const VERSION = 'dayakarya-v6';
const STATIC_CACHE = `${VERSION}-static`;
const PAGE_CACHE = `${VERSION}-pages`;
const ASSET_CACHE = `${VERSION}-assets`;
const OFFLINE_URL = '/offline.html';
const APP_SHELL = [
  '/',
  '/explore',
  '/css/app.css',
  '/js/app.js',
  '/manifest.webmanifest',
  '/img/icon.svg',
  '/img/icon-192.png',
  '/img/icon-512.png',
  OFFLINE_URL,
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then((cache) => cache.addAll(APP_SHELL))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil((async () => {
    const keys = await caches.keys();
    await Promise.all(
      keys
        .filter((key) => ![STATIC_CACHE, PAGE_CACHE, ASSET_CACHE].includes(key))
        .map((key) => caches.delete(key))
    );

    if ('navigationPreload' in self.registration) {
      await self.registration.navigationPreload.enable();
    }

    await self.clients.claim();
  })());
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  if (request.method !== 'GET') return;
  if (url.origin !== self.location.origin) return;
  if (url.pathname.startsWith('/api/')) return;

  if (request.mode === 'navigate') {
    event.respondWith(handleNavigation(request, event));
    return;
  }

  if (['style', 'script', 'font'].includes(request.destination)) {
    event.respondWith(staleWhileRevalidate(request, ASSET_CACHE));
    return;
  }

  if (request.destination === 'image' || url.pathname.startsWith('/img/')) {
    event.respondWith(cacheFirst(request, ASSET_CACHE));
    return;
  }

  event.respondWith(networkFirst(request, PAGE_CACHE));
});

async function handleNavigation(request, event) {
  try {
    const preload = await event.preloadResponse;
    if (preload) {
      putInCache(PAGE_CACHE, request, preload.clone());
      return preload;
    }

    const response = await fetch(request);
    putInCache(PAGE_CACHE, request, response.clone());
    return response;
  } catch (_) {
    return (await caches.match(request))
      || (await caches.match(OFFLINE_URL))
      || Response.error();
  }
}

async function networkFirst(request, cacheName) {
  try {
    const response = await fetch(request);
    putInCache(cacheName, request, response.clone());
    return response;
  } catch (_) {
    const cached = await caches.match(request);
    if (cached) return cached;

    const acceptsHtml = (request.headers.get('accept') || '').includes('text/html');
    if (acceptsHtml) {
      return (await caches.match(OFFLINE_URL)) || Response.error();
    }

    return Response.error();
  }
}

async function staleWhileRevalidate(request, cacheName) {
  const cached = await caches.match(request);
  const fetchPromise = fetch(request)
    .then((response) => {
      putInCache(cacheName, request, response.clone());
      return response;
    })
    .catch(() => cached);

  return cached || fetchPromise;
}

async function cacheFirst(request, cacheName) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    putInCache(cacheName, request, response.clone());
    return response;
  } catch (_) {
    return (await caches.match('/img/icon-192.png'))
      || (await caches.match(OFFLINE_URL))
      || Response.error();
  }
}

async function putInCache(cacheName, request, response) {
  if (!response || !response.ok) return;
  const cache = await caches.open(cacheName);
  await cache.put(request, response);
}
