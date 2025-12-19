// Script d'installation PWA - Version améliorée avec bouton toujours visible
let deferredPrompt;
let installButton = null;

// Créer le bouton d'installation
function createInstallButton() {
    // Supprimer l'ancien bouton s'il existe
    const oldButton = document.getElementById('pwa-install-btn');
    if (oldButton) {
        oldButton.remove();
    }

    const button = document.createElement('button');
    button.id = 'pwa-install-btn';
    button.className = 'btn btn-primary position-fixed';
    button.style.cssText = `
        bottom: 20px; 
        right: 20px; 
        z-index: 9999; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.4); 
        padding: 12px 20px; 
        font-weight: 600; 
        border-radius: 25px; 
        background: linear-gradient(135deg, #2538A1 0%, #1e2d7a 100%); 
        border: none;
        color: white;
        cursor: pointer;
        transition: transform 0.2s;
    `;
    button.innerHTML = '<i class="bi bi-download" style="margin-right: 8px;"></i> Installer Vtrack';
    
    // Effet hover
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.05)';
    });
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
    });
    
    button.addEventListener('click', async () => {
        if (deferredPrompt) {
            // Afficher le prompt d'installation
            deferredPrompt.prompt();
            
            // Attendre la réponse de l'utilisateur
            const { outcome } = await deferredPrompt.userChoice;
            
            console.log(`Résultat de l'installation: ${outcome}`);
            
            // Réinitialiser
            deferredPrompt = null;
            button.style.display = 'none';
        } else {
            // Instructions pour installation manuelle
            showInstallInstructions();
        }
    });

    document.body.appendChild(button);
    installButton = button;
    
    // Afficher le bouton après un court délai
    setTimeout(() => {
        if (installButton) {
            installButton.style.display = 'block';
        }
    }, 1000);
}

// Afficher les instructions d'installation
function showInstallInstructions() {
    const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    const isAndroid = /Android/i.test(navigator.userAgent);
    const isEdge = /Edg/i.test(navigator.userAgent);
    const isChrome = /Chrome/i.test(navigator.userAgent) && !isEdge;
    
    let message = '📱 Pour installer Vtrack :\n\n';
    
    if (isIOS) {
        message += '1. Clique sur le bouton de partage (carré avec flèche)\n';
        message += '2. Fais défiler et clique sur "Sur l\'écran d\'accueil"\n';
        message += '3. Clique sur "Ajouter"';
    } else if (isAndroid) {
        message += '1. Clique sur le menu (⋮) en haut à droite\n';
        message += '2. Clique sur "Ajouter à l\'écran d\'accueil"\n';
        message += '3. Clique sur "Ajouter"';
    } else if (isEdge || isChrome) {
        message += '1. Clique sur le menu (⋮) en haut à droite\n';
        message += '2. Cherche "Installer Vtrack..." ou "App installer"\n';
        message += '3. Clique dessus pour installer';
    } else {
        message += 'Utilise le menu de ton navigateur pour installer l\'application';
    }
    
    alert(message);
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
});

// Vérifier si l'app est déjà installée
window.addEventListener('appinstalled', () => {
    console.log('✅ PWA installée avec succès!');
    if (installButton) {
        installButton.style.display = 'none';
    }
    deferredPrompt = null;
});

// Vérifier au chargement
window.addEventListener('load', () => {
    // Si on est en mode standalone, l'app est déjà installée
    if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('✅ Application déjà installée (mode standalone)');
        return;
    }

    // Toujours créer le bouton (sera affiché même sans beforeinstallprompt)
    createInstallButton();
    
    // Vérifier le service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then((registration) => {
            console.log('✅ Service Worker prêt');
        }).catch(err => {
            console.error('❌ Erreur Service Worker:', err);
        });
    }
    
    // Diagnostic après 2 secondes
    setTimeout(() => {
        checkPWAStatus();
    }, 2000);
});

// Fonction de diagnostic
function checkPWAStatus() {
    console.log('🔍 === DIAGNOSTIC PWA ===');
    
    // Vérifier Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(registrations => {
            console.log('Service Workers:', registrations.length);
            if (registrations.length === 0) {
                console.warn('⚠️ Aucun Service Worker enregistré');
            }
        });
    }
    
    // Vérifier Manifest
    fetch('/manifest.json')
        .then(response => response.json())
        .then(manifest => {
            console.log('✅ Manifest valide:', manifest.name);
        })
        .catch(err => {
            console.error('❌ Erreur Manifest:', err);
        });
    
    // Vérifier beforeinstallprompt
    if (!deferredPrompt) {
        console.warn('⚠️ beforeinstallprompt non déclenché');
        console.log('Raisons possibles:');
        console.log('- L\'app est déjà installée');
        console.log('- Le manifest n\'est pas valide');
        console.log('- Le service worker n\'est pas actif');
        console.log('- Les critères PWA ne sont pas remplis');
    } else {
        console.log('✅ beforeinstallprompt disponible');
    }
    
    console.log('🔍 === FIN DIAGNOSTIC ===');
}

