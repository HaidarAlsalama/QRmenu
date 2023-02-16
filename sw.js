const CACHE_NAME = 'qrmenu-cache-v1';
const urlsToCache = [
  'https://qrmenu.byrings.sy/',
  'https://qrmenu.byrings.sy/login',
    'https://qrmenu.byrings.sy/manifest.json',
  'https://qrmenu.byrings.sy/js/main.js',
  'https://qrmenu.byrings.sy/css/main.css',
  'https://qrmenu.byrings.sy/img/rings.png'

];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Cache opened');
                return cache.addAll(urlsToCache);
            })
    );
});

  self.addEventListener('fetch', function(event) {
    event.respondWith(
      caches.match(event.request)
        .then(function(response) {
          if (response) {
            console.log('Cache hit for', event.request.url);
            return response;
          }
          console.log('Cache miss for', event.request.url);
          return fetch(event.request);
        })
    );
  });

  self.addEventListener('activate', function(event) {
    event.waitUntil(
      caches.keys().then(function(cacheNames) {
        return Promise.all(
          cacheNames.map(function(cacheName) {
            if (cacheName !== CACHE_NAME) {
              console.log('Deleting old cache', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
    );
  });