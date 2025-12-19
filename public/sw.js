// Service Worker pour Vtrack PWA
const CACHE_NAME = 'vtrack-v1';
const urlsToCache = [
    '/',
    '/logovalerie.jpeg'
];

// Installation du Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Cache ouvert:', CACHE_NAME);
                // Ajouter les URLs une par une pour éviter que l'échec d'une URL bloque tout
                return Promise.allSettled(
                    urlsToCache.map(url => 
                        cache.add(url).catch(err => {
                            console.warn('[SW] Impossible de mettre en cache:', url, err);
                        })
                    )
                );
            })
            .then(() => {
                console.log('[SW] Installation terminée');
                // Forcer l'activation immédiate
                return self.skipWaiting();
            })
    );
});

// Activation du Service Worker
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[SW] Suppression de l\'ancien cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
        .then(() => {
            console.log('[SW] Activation terminée');
            // Prendre le contrôle de toutes les pages
            return self.clients.claim();
        })
    );
});

// Stratégie: Network First, puis Cache
self.addEventListener('fetch', (event) => {
    // Ne pas intercepter les requêtes POST, PUT, DELETE, etc.
    if (event.request.method !== 'GET') {
        return;
    }

    // Ne pas intercepter les requêtes API
    if (event.request.url.includes('/api/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Ne mettre en cache que les réponses valides
                if (response.status === 200) {
                    const responseToCache = response.clone();
                    
                    caches.open(CACHE_NAME)
                        .then((cache) => {
                            cache.put(event.request, responseToCache);
                        });
                }
                
                return response;
            })
            .catch(() => {
                // Si le réseau échoue, utiliser le cache
                return caches.match(event.request);
            })
    );
});

