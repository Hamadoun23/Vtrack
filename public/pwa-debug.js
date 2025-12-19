// Script de diagnostic PWA
console.log('🔍 === DIAGNOSTIC PWA ===');

// 1. Vérifier le support Service Worker
console.log('1. Support Service Worker:', 'serviceWorker' in navigator);

// 2. Vérifier le manifest
fetch('/manifest.json')
    .then(response => response.json())
    .then(manifest => {
        console.log('2. Manifest:', manifest);
        console.log('   - Name:', manifest.name);
        console.log('   - Short name:', manifest.short_name);
        console.log('   - Start URL:', manifest.start_url);
        console.log('   - Display:', manifest.display);
        console.log('   - Icons:', manifest.icons.length, 'icône(s)');
    })
    .catch(err => console.error('2. Erreur manifest:', err));

// 3. Vérifier le Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(registrations => {
        console.log('3. Service Workers enregistrés:', registrations.length);
        registrations.forEach((reg, index) => {
            console.log(`   SW ${index + 1}:`, {
                scope: reg.scope,
                active: !!reg.active,
                installing: !!reg.installing,
                waiting: !!reg.waiting
            });
        });
    });
}

// 4. Vérifier le mode d'affichage
console.log('4. Mode d\'affichage:', window.matchMedia('(display-mode: standalone)').matches ? 'standalone (installé)' : 'navigateur');

// 5. Vérifier le contexte
console.log('5. Contexte:', {
    protocol: location.protocol,
    hostname: location.hostname,
    isSecure: location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1'
});

// 6. Vérifier les fichiers
const files = ['/sw.js', '/manifest.json', '/logovalerie.jpeg'];
files.forEach(file => {
    fetch(file)
        .then(response => {
            console.log(`6. ${file}:`, response.ok ? '✅ Accessible' : `❌ Erreur ${response.status}`);
        })
        .catch(err => {
            console.log(`6. ${file}:`, '❌ Erreur', err.message);
        });
});

// 7. Vérifier l'événement beforeinstallprompt
let promptEventFired = false;
window.addEventListener('beforeinstallprompt', (e) => {
    promptEventFired = true;
    console.log('7. ✅ Événement beforeinstallprompt déclenché!');
    console.log('   L\'application EST installable');
});

setTimeout(() => {
    if (!promptEventFired) {
        console.log('7. ⚠️ Événement beforeinstallprompt NON déclenché');
        console.log('   Raisons possibles:');
        console.log('   - L\'app est déjà installée');
        console.log('   - Le manifest n\'est pas valide');
        console.log('   - Le service worker n\'est pas actif');
        console.log('   - Les icônes ne sont pas accessibles');
        console.log('   - Le navigateur ne supporte pas l\'installation');
    }
}, 3000);

console.log('🔍 === FIN DIAGNOSTIC ===');

