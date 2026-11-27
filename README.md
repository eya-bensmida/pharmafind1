# PharmaFind

PharmaFind est une plateforme web développée avec Laravel qui permet de rechercher des pharmacies et de consulter la disponibilité des médicaments en Tunisie. Les pharmaciens gèrent eux-mêmes leurs stocks, tandis que les administrateurs valident les pharmacies inscrites et supervisent la plateforme.

## Table des matières

1. Contexte et objectifs
2. Fonctionnalités
3. Rôles des utilisateurs
4. Technologies utilisées
5. Architecture du projet
6. Prérequis
7. Installation
8. Configuration
9. Lancement
10. Avancement par sprint
11. Données de démonstration
12. Tests
13. Équipe


## 1. Contexte et objectifs

Trouver un médicament précis dans une pharmacie proche peut être long et incertain. PharmaFind répond à ce problème en centralisant les informations de disponibilité des médicaments et en les rendant accessibles aux utilisateurs.

Objectifs principaux :

- Faciliter la recherche de pharmacies pour les utilisateurs.
- Permettre de consulter le statut d'un médicament avant de se déplacer.
- Donner aux pharmaciens un outil simple pour gérer leurs stocks.
- Donner aux administrateurs un moyen de contrôler les pharmacies inscrites et de superviser la plateforme.
- Proposer une assistance conversationnelle grâce à un chatbot basé sur l'intelligence artificielle.

## 2. Fonctionnalités

### Utilisateurs (citoyens)

- Accès à la page d'accueil.
- Recherche de pharmacies.
- Recherche par gouvernorat et ville.
- Recherche avec géolocalisation GPS.
- Consultation des résultats.
- Assistance par chatbot multilingue (français, arabe et tunisien).

### Pharmaciens

- Demande de création d'un espace pharmacien.
- Recherche et sélection d'une pharmacie existante lors de l'inscription.
- Connexion à l'espace pharmacien.
- Gestion du profil.
- Gestion des médicaments et des stocks.
- Ajout, modification et retrait d'un médicament.

### Administrateurs

- Connexion à l'espace administrateur.
- Consultation du tableau de bord.
- Consultation et validation des demandes de pharmacies.
- Consultation des pharmacies inscrites.
- Supervision des recherches sans résultat.

## 3. Rôles des utilisateurs

| Rôle | Description |
|---|---|
| Utilisateur / Citoyen | Recherche des pharmacies et consulte les informations de disponibilité |
| Pharmacien | Gère le profil et les stocks de sa pharmacie après validation |
| Administrateur | Valide les pharmacies et supervise la plateforme |

## 4. Technologies utilisées

| Couche | Technologie |
|---|---|
| Frontend | HTML5, CSS3, JavaScript, Blade |
| Framework CSS | Bootstrap 5.3.3 |
| Backend | PHP 8.2 et Laravel 12 |
| Base de données | MySQL |
| Chatbot | Google Gemini API REST |
| Modèle IA | Gemini 2.5 Flash (gemini-2.5-flash) |
| Version de l'API Gemini | v1beta |
| Communication HTTP | Laravel HTTP Client |
| Serveur local | XAMPP / serveur de développement Laravel |
| Gestion des dépendances PHP | Composer |
| Gestion des versions | Git et GitHub |

## 5. Architecture du projet

Le projet utilise l'architecture MVC (Modèle-Vue-Contrôleur) de Laravel.


### Organisation générale

- **Models :** représentent les données et les relations avec la base MySQL.
- **Controllers :** gèrent les requêtes et les interactions entre les vues et les modèles.
- **Services :** regroupent certains traitements applicatifs, notamment le service d'intégration Gemini.
- **Views :** contiennent les interfaces Blade destinées aux citoyens, pharmaciens et administrateurs.
- **Routes :** définissent les points d'accès de l'application.
- **Database :** contient les migrations, les seeders et les factories.

## 6. Prérequis

Pour installer et exécuter PharmaFind, les outils suivants sont nécessaires :

- PHP 8.2 ou supérieur.
- Composer.
- MySQL.
- XAMPP ou un environnement PHP/MySQL équivalent.
- Git.
- Node.js et npm pour la gestion et la compilation des ressources frontend .
- Une clé API Google Gemini pour le chatbot.

## 7. Installation

### 1. Cloner le dépôt GitHub


git clone https://github.com/eya-bensmida/pharmafind1.git
cd pharmafind1


### 2. Installer les dépendances PHP


composer install


### 3. Installer les dépendances frontend

Si le projet utilise Vite :


npm install


### 4. Préparer le fichier d'environnement

Sous Windows PowerShell :


Copy-Item .env.example .env


Sous Linux ou macOS :


cp .env.example .env


### 5. Générer la clé de l'application


php artisan key:generate


### 6. Créer la base de données

Créer une base de données vide nommée `pharmafind` dans MySQL (par exemple avec phpMyAdmin sous XAMPP), puis configurer le fichier `.env` comme indiqué dans la section suivante.

### 7. Créer les tables et charger les données initiales


php artisan migrate --seed


### 8. Créer le lien de stockage

Cette commande rend accessibles les fichiers téléversés (par exemple les justificatifs des pharmacies) :


php artisan storage:link


## 8. Configuration

Modifier le fichier `.env` à la racine du projet.

### Configuration de la base de données


APP_NAME=PharmaFind
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmafind
DB_USERNAME=root
DB_PASSWORD=


Les identifiants doivent être adaptés à l'installation locale de MySQL.

### Configuration du chatbot Gemini

Ajouter les variables suivantes dans `.env` :


GEMINI_API_KEY=VOTRE_CLE_API_GEMINI
GEMINI_MODEL=gemini-2.5-flash


Le chatbot utilise l'API Google Gemini REST avec l'endpoint v1beta et la méthode generateContent.

**Important :** ne jamais publier le fichier `.env` ni la clé API sur GitHub.

Après une modification de configuration :


php artisan config:clear


## 9. Lancement

### Démarrer le serveur Laravel

À la racine du projet :


php artisan serve


L'application sera accessible à l'adresse :


http://127.0.0.1:8000


### Compiler les ressources frontend

Pour le développement :


npm run dev


Pour générer les ressources de production :

npm run build


Ces commandes sont à utiliser si le projet dispose de la configuration Vite correspondante.

## 10. Avancement par sprint

| Sprint | Thème | Statut |
|---|---|---|
| Sprint 1 | Authentification administrateur, gestion des pharmacies et interface administrative | Terminé |
| Sprint 2 | Espace pharmacien et gestion des médicaments et des stocks | Terminé |
| Sprint 3 | Recherche citoyenne, géolocalisation, résultats et chatbot IA | En cours |

Le détail des tâches et des livrables est disponible dans le dossier `docs/`, s'il est présent dans le dépôt.

## 11. Données de démonstration

Pour tester la validation d'une demande de pharmacie, des documents fictifs peuvent être utilisés dans le dossier `docs/specimens/` :

- `specimen_autorisation_pharmacie_tunisie.pdf`
- `specimen_justificatif_ordre_pharmacien_tunisie.pdf`

Ces fichiers sont des maquettes de test. Ils ne sont pas des documents officiels et ne doivent jamais être présentés comme de vrais justificatifs.

En production, le demandeur doit téléverser les documents authentiques et l'administrateur doit les vérifier auprès des autorités compétentes.

### Comptes de démonstration

Les identifiants des comptes administrateur et pharmacien doivent être communiqués séparément si des comptes de test sont configurés.

## 12. Tests

Laravel fournit une commande permettant d'exécuter les tests automatisés du projet :


php artisan test


Pour vérifier également les routes enregistrées :


php artisan route:list


## 13. Équipe

| Nom | Rôle |
|---|---|
| Ghofrane Rhouma | Développement |
| Eya Bensmida | Développement |
| Eya Bensmida et Ghofrane Rhouma | Base de données et intégration |

Projet réalisé dans le cadre du module 'projet d'integration', encadré par Madame Chifa Laabidi.
