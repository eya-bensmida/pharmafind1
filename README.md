# PharmaFind

PharmaFind est une plateforme web qui permet de rechercher des pharmacies et de consulter la disponibilité des médicaments en Tunisie. Les pharmaciens gèrent eux-mêmes leur stock de médicaments, et les administrateurs valident les pharmacies et suivent les recherches restées sans résultat.

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
14. Licence

## 1. Contexte et objectifs

Trouver un médicament précis dans une pharmacie proche peut être long et incertain. PharmaFind répond à ce problème en centralisant la disponibilité des médicaments et en la rendant accessible à tous.

Objectifs principaux :

- Faciliter la recherche de pharmacies pour les utilisateurs.
- Permettre de consulter le statut d'un médicament avant de se déplacer.
- Donner aux pharmaciens un outil simple pour mettre à jour leur disponibilité.
- Donner aux administrateurs un moyen de contrôler les pharmacies inscrites et d'identifier les médicaments demandés mais introuvables.

## 2. Fonctionnalités

### Utilisateurs
- Recherche de pharmacies.
- Consultation du statut des médicaments.
- Assistance par chatbot.

### Pharmaciens
- Demande de création de compte pharmacien.
- Connexion à l'espace pharmacien.
- Gestion du profil.
- Ajout, modification et retrait d'un médicament.

### Administrateurs
- Connexion à l'espace administrateur.
- Gestion des pharmacies (consultation, validation des demandes).
- Consultation des recherches infructueuses.

## 3. Rôles des utilisateurs

| Rôle | Description |
|---|---|
| Utilisateur | Recherche des pharmacies et consulte la disponibilité des médicaments |
| Pharmacien | Gère les médicaments de sa pharmacie après validation de son compte |
| Administrateur | Valide les pharmacies et supervise la plateforme |

## 4. Technologies utilisées

| Couche | Technologie |
|---|---|
| Frontend | [ex. React, Angular, HTML/CSS/JS] |
| Backend | [ex. Node.js/Express, Laravel, Django] |
| Base de données | [ex. MySQL, PostgreSQL, MongoDB] |
| Chatbot | [ex. API utilisée] |
| Outils | Git, GitHub, [autres] |

## 5. Architecture du projet

```
pharmafind/
  frontend/        Interface utilisateur
  backend/         API et logique métier
  docs/            Documentation (sprints, spécifications)
  README.md
```

Adaptez cette arborescence à la structure réelle de votre dépôt.

## 6. Prérequis

- [Node.js version X ou supérieure / PHP / Python]
- [Gestionnaire de paquets : npm, composer, pip]
- [Système de base de données installé et démarré]
- Git

## 7. Installation

1. Cloner le dépôt :

```
git clone [URL_DU_DEPOT]
cd pharmafind
```

2. Installer les dépendances du backend :

```
cd backend
[commande d'installation, ex. npm install]
```

3. Installer les dépendances du frontend :

```
cd ../frontend
[commande d'installation, ex. npm install]
```

## 8. Configuration

Créez un fichier d'environnement dans le backend (par exemple `.env`) à partir du modèle fourni, puis renseignez les valeurs :

```
DB_HOST=[hôte de la base de données]
DB_NAME=[nom de la base]
DB_USER=[utilisateur]
DB_PASSWORD=[mot de passe]
JWT_SECRET=[clé secrète]
PORT=[port du serveur]
```

Ne publiez jamais ce fichier sur GitHub. Vérifiez qu'il figure dans le `.gitignore`.

## 9. Lancement

Démarrer le backend :

```
cd backend
[commande de lancement, ex. npm start]
```

Démarrer le frontend :

```
cd frontend
[commande de lancement, ex. npm run dev]
```

L'application est ensuite accessible à l'adresse : [http://localhost:PORT]

## 10. Avancement par sprint

Le détail de chaque sprint se trouve dans le dossier `docs/`.

| Sprint | Thème | Statut |
|---|---|---|
| Sprint 1 | Authentification administrateur, gestion des pharmacies, recherches infructueuses | Terminé |
| Sprint 2 | Espace pharmacien et gestion de la disponibilité des médicaments | Terminé |
| Sprint 3 | Recherche de pharmacies, statut des médicaments, chatbot | [En cours / Terminé] |

## 11. Données de démonstration

Pour tester la validation d'une demande de pharmacie, des documents fictifs sont fournis dans le dossier `docs/specimens/` :

- `specimen_autorisation_pharmacie_tunisie.pdf`
- `specimen_justificatif_ordre_pharmacien_tunisie.pdf`

Ces fichiers sont des maquettes de test. Ils ne sont pas des documents officiels et ne doivent jamais être présentés comme de vrais justificatifs. En production, le demandeur doit téléverser les documents authentiques, et l'administrateur doit les vérifier auprès des autorités compétentes.

Comptes de test : [identifiants de démonstration administrateur et pharmacien, si applicable]

## 12. Tests

```
[commande de lancement des tests, si elle existe]
```

## 13. Équipe

| Nom | Rôle |
|---|---|
| [Nom Prénom] | [Rôle] |
| [Nom Prénom] | [Rôle] |

Projet réalisé dans le cadre de [nom du module / de la formation], encadré par [nom de l'encadrant].

## 14. Licence

Projet académique. [Précisez la licence ou les droits d'utilisation.]
