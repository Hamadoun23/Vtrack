# 📱 Vtrack - Système de Gestion de Véhicules et SIM

**Vtrack** est une application web progressive (PWA) développée avec Laravel 10 pour la gestion complète de clients, véhicules, cartes SIM et interventions. L'application offre une interface moderne et intuitive pour suivre l'ensemble de votre flotte de véhicules.

## 📋 Table des matières

1. [Présentation](#présentation)
2. [Technologies utilisées](#technologies-utilisées)
3. [Fonctionnalités](#fonctionnalités)
4. [Installation](#installation)
5. [Configuration](#configuration)
6. [Structure de la base de données](#structure-de-la-base-de-données)
7. [Architecture du projet](#architecture-du-projet)
8. [Utilisation](#utilisation)
9. [PWA (Progressive Web App)](#pwa-progressive-web-app)
10. [Déploiement](#déploiement)
11. [Sécurité](#sécurité)

---

## 🎯 Présentation

Vtrack est une solution complète de gestion de flotte permettant de :
- Gérer les clients et leurs informations de contact
- Suivre les véhicules avec leurs immatriculations et statuts
- Administrer les cartes SIM et leur association aux véhicules
- Enregistrer et suivre les interventions effectuées sur les véhicules
- Exporter toutes les données au format CSV/Excel
- Rechercher rapidement dans l'ensemble des données

L'application est conçue comme une **Progressive Web App (PWA)**, ce qui permet son installation sur mobile et desktop pour une utilisation hors ligne.

---

## 🛠 Technologies utilisées

### Backend
- **Laravel 10** : Framework PHP moderne et robuste
- **PHP 8.1+** : Langage de programmation
- **MySQL** : Base de données relationnelle
- **Laravel Breeze** : Authentification simplifiée
- **Laravel Sanctum** : Authentification API

### Frontend
- **Bootstrap 5.3** : Framework CSS pour l'interface utilisateur
- **Bootstrap Icons** : Bibliothèque d'icônes
- **JavaScript (Vanilla)** : Interactivité côté client
- **Vite** : Build tool pour les assets frontend

### PWA
- **Service Worker** : Cache et fonctionnement hors ligne
- **Web App Manifest** : Installation sur appareils

---

## ✨ Fonctionnalités

### 1. Gestion des Clients
- ✅ Création, modification et suppression de clients
- ✅ Informations : nom, contact, notes
- ✅ Visualisation des véhicules associés à chaque client
- ✅ Liste complète avec recherche

### 2. Gestion des Véhicules
- ✅ CRUD complet (Create, Read, Update, Delete)
- ✅ Champs obligatoires : immatriculation (unique), statut
- ✅ Champs optionnels : client associé
- ✅ Association avec une carte SIM
- ✅ Statuts : `actif` ou `suspendu`
- ✅ Suspension/réactivation avec raison
- ✅ Remplacement de SIM
- ✅ Historique des interventions

### 3. Gestion des Cartes SIM
- ✅ CRUD complet
- ✅ Champs obligatoires : `last5` (5 derniers chiffres), statut
- ✅ Champs optionnels : ICCID, numéro complet, opérateur
- ✅ Statuts : `active`, `bloquee`, `inactive`
- ✅ Blocage/déblocage avec raison
- ✅ Visualisation du véhicule associé et du client

### 4. Interventions
- ✅ Enregistrement d'interventions par véhicule
- ✅ Description détaillée
- ✅ Date d'intervention
- ✅ Historique complet par véhicule
- ✅ Suppression d'interventions

### 5. Dashboard
- ✅ Statistiques en temps réel :
  - Nombre total de clients, véhicules, SIM
  - Véhicules actifs/suspendus
  - SIM actives/bloquées/inactives
  - Interventions du mois
- ✅ Notifications :
  - Véhicules suspendus récents
  - SIM bloquées récentes
  - Véhicules sans SIM
  - SIM non assignées
- ✅ Raccourcis rapides pour ajouter véhicules, SIM, clients
- ✅ Liste des derniers véhicules ajoutés
- ✅ Dernières interventions

### 6. Recherche
- ✅ Recherche globale dans véhicules, SIM et clients
- ✅ Recherche intelligente par :
  - Immatriculation (début de chaîne)
  - Last5 de SIM (début de chaîne)
  - ICCID (début de chaîne)
  - Numéro de SIM (début de chaîne)
  - Nom de client

### 7. Export de données
- ✅ Export complet de la base de données au format CSV
- ✅ Compatible Excel
- ✅ Sections : Users, Clients, SIMs, Véhicules, Interventions
- ✅ Encodage UTF-8 avec BOM pour Excel

### 8. Authentification
- ✅ Connexion sécurisée avec Laravel Breeze
- ✅ Hachage des mots de passe (bcrypt)
- ✅ Gestion de profil utilisateur
- ✅ Protection des routes par middleware `auth`
- ⚠️ Pas de page d'inscription (création manuelle des utilisateurs)

---

## 📦 Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7+ ou MariaDB
- Node.js et npm
- Git (optionnel)

### Étapes d'installation

1. **Cloner le projet** (ou télécharger)
```bash
git clone <url-du-repo>
cd Ztrack
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances frontend**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**
Éditez le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vtrack
DB_USERNAME=root
DB_PASSWORD=
```

6. **Créer la base de données**
```sql
CREATE DATABASE vtrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

7. **Exécuter les migrations**
```bash
php artisan migrate
```

8. **Compiler les assets frontend**
```bash
npm run build
```

9. **Lancer le serveur de développement**
```bash
php artisan serve
```

L'application est accessible sur `http://127.0.0.1:8000`

### Créer un utilisateur

L'application n'a pas de page d'inscription. Créez un utilisateur via Tinker :

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'nom' => 'Nom',
    'prenom' => 'Prénom',
    'email' => 'email@example.com',
    'password' => Hash::make('motdepasse')
]);
```

---

## ⚙️ Configuration

### Fichier `.env`

Variables importantes :
```env
APP_NAME=Vtrack
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vtrack
DB_USERNAME=root
DB_PASSWORD=

# Pour la production
APP_ENV=production
APP_DEBUG=false
```

### Permissions

Assurez-vous que les dossiers suivants sont accessibles en écriture :
```bash
storage/
storage/framework/
storage/logs/
bootstrap/cache/
```

---

## 🗄 Structure de la base de données

### Table `users`
- `id_user` (PK) : Identifiant unique
- `nom` : Nom de l'utilisateur
- `prenom` : Prénom de l'utilisateur
- `email` : Email (unique)
- `password` : Mot de passe haché
- `email_verified_at` : Date de vérification email (nullable)
- `remember_token` : Token de session
- `created_at`, `updated_at` : Timestamps

### Table `clients`
- `id_client` (PK) : Identifiant unique
- `nom` : Nom du client (obligatoire)
- `contact` : Informations de contact (nullable)
- `note` : Notes additionnelles (nullable)
- `created_at`, `updated_at` : Timestamps

### Table `sims`
- `id_sim` (PK) : Identifiant unique
- `iccid` : Code ICCID (nullable)
- `last5` : 5 derniers chiffres (obligatoire)
- `numero` : Numéro complet (nullable)
- `operateur` : Opérateur téléphonique (nullable)
- `statut` : `active`, `bloquee`, `inactive` (obligatoire)
- `raison_blocage` : Raison du blocage (nullable)
- `created_at`, `updated_at` : Timestamps

### Table `vehicules`
- `id_vehicule` (PK) : Identifiant unique
- `immatriculation` : Plaque d'immatriculation (unique, obligatoire)
- `client_id` (FK) : Référence au client (nullable)
- `sim_id` (FK) : Référence à la SIM (nullable, unique)
- `statut` : `actif` ou `suspendu` (obligatoire)
- `raison_suspension` : Raison de suspension (nullable)
- `created_at`, `updated_at` : Timestamps

### Table `interventions`
- `id_intervention` (PK) : Identifiant unique
- `vehicule_id` (FK) : Référence au véhicule (obligatoire)
- `description` : Description de l'intervention (obligatoire)
- `date_intervention` : Date de l'intervention (obligatoire)
- `created_at`, `updated_at` : Timestamps

### Relations
- **Client** `hasMany` **Véhicules**
- **Véhicule** `belongsTo` **Client**
- **Véhicule** `belongsTo` **SIM**
- **SIM** `hasOne` **Véhicule**
- **Véhicule** `hasMany` **Interventions**
- **Intervention** `belongsTo` **Véhicule**

---

## 🏗 Architecture du projet

### Structure des dossiers

```
Ztrack/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Contrôleurs MVC
│   │   │   ├── DashboardController.php
│   │   │   ├── ClientController.php
│   │   │   ├── VehiculeController.php
│   │   │   ├── SimController.php
│   │   │   ├── InterventionController.php
│   │   │   └── SearchController.php
│   │   └── Middleware/       # Middlewares
│   └── Models/              # Modèles Eloquent
│       ├── User.php
│       ├── Client.php
│       ├── Vehicule.php
│       ├── Sim.php
│       └── Intervention.php
├── database/
│   └── migrations/          # Migrations de base de données
├── public/
│   ├── index.php           # Point d'entrée
│   ├── manifest.json        # Manifest PWA
│   ├── sw.js               # Service Worker
│   └── assets/             # Assets compilés
├── resources/
│   ├── views/              # Vues Blade
│   │   ├── layouts/
│   │   ├── dashboard/
│   │   ├── clients/
│   │   ├── vehicules/
│   │   ├── sims/
│   │   └── auth/
│   ├── css/                # Styles CSS
│   └── js/                 # JavaScript
├── routes/
│   ├── web.php             # Routes web
│   └── auth.php            # Routes d'authentification
└── config/                 # Fichiers de configuration
```

### Contrôleurs principaux

#### `DashboardController`
- `index()` : Affiche le dashboard avec statistiques
- `exportCsv()` : Export de toutes les données en CSV

#### `ClientController`
- CRUD complet pour les clients

#### `VehiculeController`
- CRUD complet pour les véhicules
- `suspendre()` : Suspendre un véhicule
- `reactiver()` : Réactiver un véhicule
- `remplacerSim()` : Remplacer la SIM d'un véhicule

#### `SimController`
- CRUD complet pour les SIM
- `bloquer()` : Bloquer une SIM
- `debloquer()` : Débloquer une SIM

#### `InterventionController`
- `store()` : Créer une intervention
- `destroy()` : Supprimer une intervention

#### `SearchController`
- `search()` : Recherche globale dans véhicules, SIM et clients

### Routes principales

```php
// Routes publiques
GET  /                    → Redirection vers dashboard
GET  /login              → Page de connexion
POST /login              → Traitement de la connexion
POST /logout             → Déconnexion

// Routes protégées (auth)
GET  /dashboard          → Dashboard principal
GET  /dashboard/export-csv → Export CSV

// Clients
GET    /clients          → Liste des clients
GET    /clients/create   → Formulaire de création
POST   /clients          → Création
GET    /clients/{id}     → Détails
GET    /clients/{id}/edit → Formulaire d'édition
PUT    /clients/{id}     → Mise à jour
DELETE /clients/{id}     → Suppression

// Véhicules
GET    /vehicules                    → Liste
POST   /vehicules/{id}/suspendre     → Suspendre
POST   /vehicules/{id}/reactiver     → Réactiver
POST   /vehicules/{id}/remplacer-sim → Remplacer SIM

// SIM
POST   /sims/{id}/bloquer   → Bloquer
POST   /sims/{id}/debloquer → Débloquer

// Interventions
POST   /vehicules/{id}/interventions → Créer
DELETE /interventions/{id}           → Supprimer

// Recherche
GET  /search?q=terme → Recherche globale
```

---

## 📖 Utilisation

### Connexion
1. Accédez à `http://127.0.0.1:8000`
2. Connectez-vous avec vos identifiants
3. Vous êtes redirigé vers le dashboard

### Ajouter un client
1. Menu → **Clients** → **Ajouter un client**
2. Remplissez le formulaire (nom obligatoire, contact et note optionnels)
3. Cliquez sur **Enregistrer**

### Ajouter un véhicule
1. Menu → **Véhicules** → **Ajouter un véhicule**
2. Remplissez :
   - Immatriculation (obligatoire, unique)
   - Statut (obligatoire)
   - Client (optionnel)
   - SIM (optionnel)
3. Cliquez sur **Enregistrer**

### Ajouter une SIM
1. Menu → **SIM** → **Ajouter une SIM**
2. Remplissez :
   - Last5 (obligatoire, 5 derniers chiffres)
   - Statut (obligatoire)
   - ICCID, Numéro, Opérateur (optionnels)
3. Cliquez sur **Enregistrer**

### Gérer les interventions
1. Allez sur la page de détails d'un véhicule
2. Section **Interventions**
3. Remplissez le formulaire :
   - Description (obligatoire)
   - Date d'intervention (obligatoire)
4. Cliquez sur **Ajouter**

### Rechercher
1. Utilisez la barre de recherche dans le dashboard
2. Tapez un terme (immatriculation, last5, nom client, etc.)
3. Les résultats s'affichent automatiquement

### Exporter les données
1. Dashboard → Bouton **"Exporter en CSV"**
2. Le fichier CSV est téléchargé
3. Ouvrez-le dans Excel ou un tableur

---

## 📱 PWA (Progressive Web App)

Vtrack est une **Progressive Web App**, ce qui signifie :

### Fonctionnalités PWA
- ✅ **Installable** : Peut être installée sur mobile et desktop
- ✅ **Hors ligne** : Fonctionne sans connexion (pages en cache)
- ✅ **Apparence native** : Mode standalone sans barre d'adresse
- ✅ **Thème personnalisé** : Couleur #2538A1
- ✅ **Icône personnalisée** : Logo Valerietech

### Installation

#### Sur Desktop (Chrome/Edge)
1. Le bouton **"Installer Vtrack"** apparaît en bas à droite
2. Ou menu navigateur (⋮) → **Installer Vtrack...**

#### Sur Mobile (Android)
1. Chrome affiche automatiquement un banner d'installation
2. Ou menu (⋮) → **Ajouter à l'écran d'accueil**

#### Sur Mobile (iOS)
1. Safari → Bouton de partage (carré avec flèche)
2. **"Sur l'écran d'accueil"** → **"Ajouter"**

### Fichiers PWA
- `public/manifest.json` : Configuration PWA
- `public/sw.js` : Service Worker pour le cache
- `public/pwa-install-fixed.js` : Script d'installation
- `public/register-sw.js` : Enregistrement du Service Worker

---

## 🚀 Déploiement

### Préparation pour la production

1. **Optimiser les dépendances**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

2. **Configurer `.env`**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

3. **Nettoyer les caches**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

4. **Optimiser**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Déploiement sur cPanel

#### 1. Transférer les fichiers modifiés

**Fichiers à transférer** (via FTP/SFTP ou File Manager) :

**Migrations :**
- `database/migrations/2025_12_13_213600_clients.php` (contact nullable)
- `database/migrations/2025_12_13_213612_sims.php` (last5 au lieu de last4)
- `database/migrations/2025_12_19_114339_rename_last4_to_last5_in_sims_table.php` (nouvelle migration)

**Modèles :**
- `app/Models/Client.php`
- `app/Models/Sim.php`

**Contrôleurs :**
- `app/Http/Controllers/ClientController.php`
- `app/Http/Controllers/SimController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/SearchController.php`

**Vues :**
- `resources/views/clients/create.blade.php`
- `resources/views/clients/edit.blade.php`
- `resources/views/sims/create.blade.php`
- `resources/views/sims/edit.blade.php`
- `resources/views/sims/show.blade.php`
- `resources/views/sims/index.blade.php`
- `resources/views/vehicules/show.blade.php`
- `resources/views/vehicules/index.blade.php`
- `resources/views/dashboard/index.blade.php`
- `resources/views/search/results.blade.php`

**Autres fichiers :**
- `routes/web.php` (si modifié)
- `README.md` (optionnel)

#### 2. Mettre à jour la base de données

**Option A : Via phpMyAdmin (cPanel)**

Exécutez ces commandes SQL dans phpMyAdmin :

```sql
-- Rendre le champ contact nullable pour les clients
ALTER TABLE clients MODIFY contact VARCHAR(255) NULL;

-- Renommer last4 en last5 et modifier la longueur (si la migration n'a pas été exécutée)
ALTER TABLE sims CHANGE last4 last5 VARCHAR(5) NOT NULL;
```

**Option B : Via SSH (si disponible)**

```bash
cd /chemin/vers/votre/projet
php artisan migrate
```

#### 3. Nettoyer les caches

Via SSH ou créez un fichier temporaire `clear_cache.php` dans `public/` :

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
Artisan::call('cache:clear');
echo "Caches nettoyés !";
```

Puis supprimez ce fichier après exécution.

#### 4. Vérifier les permissions

Assurez-vous que les dossiers suivants ont les bonnes permissions (755 ou 775) :
- `storage/`
- `storage/framework/`
- `storage/logs/`
- `bootstrap/cache/`

#### 5. Compiler les assets (si nécessaire)

Si vous avez modifié des fichiers CSS/JS, compilez-les en local puis transférez le dossier `public/build/` :

```bash
npm run build
```

### Exigences serveur
- PHP 8.1+
- Extensions PHP : `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `json`
- MySQL 5.7+ ou MariaDB
- HTTPS (requis pour PWA en production)

---

## 🔒 Sécurité

### Mesures de sécurité implémentées
- ✅ **Hachage des mots de passe** : bcrypt
- ✅ **Protection CSRF** : Tokens sur tous les formulaires
- ✅ **Authentification** : Middleware `auth` sur toutes les routes
- ✅ **Validation** : Validation des données côté serveur
- ✅ **Protection SQL Injection** : Eloquent ORM
- ✅ **XSS Protection** : Échappement automatique dans Blade

### Bonnes pratiques
- Ne jamais commiter le fichier `.env`
- Utiliser des mots de passe forts
- Mettre à jour régulièrement les dépendances
- Activer HTTPS en production
- Configurer correctement les permissions de fichiers

---

## 📝 Notes importantes

### Champs optionnels
- **Clients** : `contact`, `note` sont optionnels
- **Véhicules** : `client_id` est optionnel
- **SIM** : `iccid`, `numero`, `operateur` sont optionnels

### Contraintes
- Une SIM ne peut être associée qu'à un seul véhicule
- L'immatriculation doit être unique
- Le `last5` d'une SIM doit être unique

### Statuts
- **Véhicules** : `actif`, `suspendu`
- **SIM** : `active`, `bloquee`, `inactive`

---

## 🐛 Dépannage

### Problème : Service Worker ne s'enregistre pas
- Vérifiez que vous êtes en HTTPS (ou localhost)
- Vérifiez la console pour les erreurs
- Videz le cache du navigateur

### Problème : PDO non trouvé
- Activez l'extension `pdo` et `pdo_mysql` dans PHP
- Vérifiez votre configuration PHP

### Problème : Erreur 500
- Vérifiez les logs : `storage/logs/laravel.log`
- Vérifiez les permissions des dossiers
- Vérifiez la configuration de la base de données

---

## 📄 Licence

Ce projet est propriétaire. Tous droits réservés.

---

## 👥 Support

Pour toute question ou problème, contactez l'équipe de développement.

---

## 📋 Modifications récentes (19 Décembre 2025)

### Version actuelle
- ✅ **Champ `contact` des clients** : Rendu optionnel (nullable)
- ✅ **Champ `last4` renommé en `last5`** : Les SIM utilisent maintenant 5 chiffres au lieu de 4
- ✅ **Boutons de suppression** : Ajoutés pour les véhicules et SIM dans les listes
- ✅ **Recherche améliorée** : Recherche par nom, prénom et téléphone des clients
- ✅ **Export CSV** : Fonctionnalité d'export complet de la base de données

### Fichiers modifiés récemment
- Migrations : `clients.php`, `sims.php`, `rename_last4_to_last5_in_sims_table.php`
- Modèles : `Client.php`, `Sim.php`
- Contrôleurs : `ClientController.php`, `SimController.php`, `DashboardController.php`, `SearchController.php`
- Vues : Toutes les vues liées aux clients, SIM et véhicules

---

**Dernière mise à jour** : 21 Décembre 2025
