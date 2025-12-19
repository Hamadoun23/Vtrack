// Script d'installation PWA avec bouton manuel
let deferredPrompt;
let installButton = null;

// Créer le bouton d'installation
function createInstallButton() {
    // Vérifier si le bouton existe déjà
    if (document.getElementById('pwa-install-btn')) {
        return;
    }

    const button = document.createElement('button');
    button.id = 'pwa-install-btn';
    button.className = 'btn btn-primary position-fixed';
    button.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.4); display: none; padding: 12px 20px; font-weight: 600; border-radius: 25px; background: linear-gradient(135deg, #2538A1 0%, #1e2d7a 100%); border: none;';
    button.innerHTML = '<i class="bi bi-download" style="margin-right: 8px;"></i> Installer Vtrack';
    
    // Effet hover
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.05)';
        button.style.transition = 'transform 0.2s';
    });
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
    });
    
    button.addEventListener('click', async () => {
        if (!deferredPrompt) {
            // Instructions pour installation manuelle
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            const isAndroid = /Android/i.test(navigator.userAgent);
            
            let message = 'Pour installer l\'application :\n\n';
            
            if (isIOS) {
                message += '1. Clique sur le bouton de partage (carré avec flèche)\n';
                message += '2. Fais défiler et clique sur "Sur l\'écran d\'accueil"\n';
                message += '3. Clique sur "Ajouter"';
            } else if (isAndroid) {
                message += '1. Clique sur le menu (⋮) en haut à droite\n';
                message += '2. Clique sur "Ajouter à l\'écran d\'accueil"\n';
                message += '3. Clique sur "Ajouter"';
            } else {
                message += '1. Clique sur le menu (⋮) en haut à droite\n';
                message += '2. Clique sur "Installer Vtrack..." ou "Add to desktop"';
            }
            
            alert(message);
            return;
        }

        // Afficher le prompt d'installation
        deferredPrompt.prompt();
        
        // Attendre la réponse de l'utilisateur
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`Résultat de l'installation: ${outcome}`);
        
        // Réinitialiser
        deferredPrompt = null;
        button.style.display = 'none';
    });

    document.body.appendChild(button);
    installButton = button;
}

// Capturer l'événement beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('✅ Événement beforeinstallprompt déclenché - PWA installable!');
    
    // Empêcher l'affichage automatique du prompt
    e.preventDefault();
    
    // Sauvegarder l'événement pour l'utiliser plus tard
    deferredPrompt = e;
    
    // Créer et afficher le bouton
    createInstallButton();
    if (installButton) {
        installButton.style.display = 'block';
    }
});

// Vérifier si l'app est déjà installée
window.addEventListener('appinstalled', () => {
    console.log('✅ PWA installée avec succès!');
    if (installButton) {
        installButton.style.display = 'none';
    }
    deferredPrompt = null;
});

// Vérifier au chargement si l'app peut être installée
window.addEventListener('load', () => {
    // Si on est en mode standalone, l'app est déjà installée
    if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('✅ Application déjà installée (mode standalone)');
        if (installButton) {
            installButton.style.display = 'none';
        }
        return;
    }

    // Créer le bouton au chargement
    createInstallButton();

    // Vérifier si le service worker est actif
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then((registration) => {
            console.log('✅ Service Worker prêt, vérification de l\'installabilité...');
            
            // Vérifier si on est sur mobile (meilleure compatibilité PWA)
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Attendre un peu pour voir si beforeinstallprompt se déclenche
            setTimeout(() => {
                if (!deferredPrompt && installButton) {
                    // Sur mobile, afficher quand même le bouton (installation manuelle possible)
                    if (isMobile) {
                        installButton.style.display = 'block';
                        installButton.innerHTML = '<i class="bi bi-download"></i> Installer sur l\'écran d\'accueil';
                        console.log('📱 Mode mobile détecté - Bouton d\'installation affiché');
                    } else {
                        // Sur desktop, cacher si beforeinstallprompt ne s'est pas déclenché
                        installButton.style.display = 'none';
                    }
                } else if (deferredPrompt && installButton) {
                    // Afficher le bouton si beforeinstallprompt s'est déclenché
                    installButton.style.display = 'block';
                }
            }, 2000);
        });
    } else {
        // Si pas de support Service Worker, créer quand même le bouton pour mobile
        createInstallButton();
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (isMobile && installButton) {
            installButton.style.display = 'block';
            installButton.innerHTML = '<i class="bi bi-download"></i> Installer sur l\'écran d\'accueil';
        }
    }
});

// Créer le bouton au chargement (il sera affiché si beforeinstallprompt se déclenche)
createInstallButton();

