// Bereso
// BEst REcipe SOftware
// ###################################
// service Worker
// included by templates/js/service_worker_register.js
// ###################################

// simple serviceWorker that only caches one offline-page to show if network is not available

const cacheName = 'BeReSoOfflineV1';

// cache static included files
const cacheAssets = [
    'index.php?module=offline'
];

// Install serviceWorker
self.addEventListener('install', event => {
    console.log('serviceWorker: installed');

    event.waitUntil(
        caches
            .open(cacheName)
            .then(cache => {
                console.log('serviceWorker: caching');
                cache.addAll(cacheAssets);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate serviceWorker
self.addEventListener('activate', event => {
    console.log('serviceWorker: activated');
    // delete old caches
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== cacheName) {
                        console.log('serviceWorker: clearing old cache');
                        return caches.delete(cache);
                    }
                })
            );
        })
    );

});

// fetching serviceWorker - when offline return cachted index.php?module=offline
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request)
            .then(function (response) { // server responding
                return response;
            })
            .catch(function (error) { // server not responding
                // Use offline page from cache - serve whenever the matching file is not found
                const cachedResponse = caches.match('index.php?module=offline');
                if (cachedResponse) return cachedResponse;
            })
    );
});
