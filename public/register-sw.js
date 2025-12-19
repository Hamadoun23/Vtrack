// Enregistrement du Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' })
            .then((registration) => {
                console.log('✅ Service Worker enregistré avec succès!');
                console.log('Scope:', registration.scope);
                console.log('Active:', registration.active);
                console.log('Installing:', registration.installing);
                console.log('Waiting:', registration.waiting);
                
                // Vérifier les mises à jour
                registration.addEventListener('updatefound', () => {
                    console.log('🔄 Nouvelle version du Service Worker trouvée');
                });
            })
            .catch((error) => {
                console.error('❌ Échec de l\'enregistrement du Service Worker:', error);
                console.error('Détails:', error.message);
            });
        
        // Vérifier si un service worker est déjà actif
        navigator.serviceWorker.ready.then((registration) => {
            console.log('✅ Service Worker prêt:', registration);
        });
    });
} else {
    console.warn('⚠️ Service Worker non supporté par ce navigateur');
}

