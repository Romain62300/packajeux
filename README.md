# Packajeux 🎮

> Plateforme de mini-jeux en ligne développée en PHP/MySQL.

[![GitHub repo size](https://img.shields.io/github/repo-size/Romain62300/packajeux)](https://github.com/Romain62300/packajeux)
[![GitHub last commit](https://img.shields.io/github/last-commit/Romain62300/packajeux)](https://github.com/Romain62300/packajeux/commits/main)
[![License: CC BY-NC 4.0](https://img.shields.io/badge/License-CC%20BY--NC%204.0-lightgrey.svg)](https://creativecommons.org/licenses/by-nc/4.0/)

![Aperçu de Packajeux](./public/assets/images/apercu-Packajeux.png)

---

## 📋 À propos

**Packajeux** est un projet personnel de développement web full-stack, commencé en avril 2025.

L’objectif est de construire une plateforme de mini-jeux jouables directement dans le navigateur, tout en travaillant sur un projet complet : conception, développement, base de données, sécurité et déploiement.

Développé par **Romain Monier**, dans le cadre de mes projets personnels et de mon activité **Alakachan Dev**.

---

## ✨ Fonctionnalités

### 🎮 Jeux disponibles

| Jeu                        | Description                             | Statut      |
| -------------------------- | --------------------------------------- | ----------- |
| **Morpion**                | 3×3 avec mode 2 joueurs et IA (minimax) | ✅           |
| **Pierre-Feuille-Ciseaux** | Contre l’ordinateur avec score          | ✅           |
| **Devine le nombre**       | Nombre mystère entre 1 et 100           | ✅           |
| **Jeu de Mémoire**         | 4×4 avec compteur de temps et coups     | ✅           |
| **Belote**                 | Jeu de cartes à 4 joueurs               | 🚧 En cours |

### 🛠️ Fonctionnalités globales

* 👤 Authentification (inscription, connexion, sessions)
* 🌓 Mode sombre avec persistance
* 📱 Interface responsive (mobile / desktop)
* 🪙 Système de jetons virtuels
* 🎨 Interface cohérente avec logo personnalisé
* ⚖️ Pages légales (mentions, CGU, confidentialité)

---

## 🔧 Stack technique

* **Backend** : PHP 8 (sans framework)
* **Base de données** : MySQL
* **Frontend** : HTML5, CSS3, JavaScript (vanilla)
* **Sécurité** :

  * PDO avec requêtes préparées
  * `password_hash()` pour les mots de passe
  * `htmlspecialchars()` pour prévenir les failles XSS
* **Versioning** : Git / GitHub
* **Environnement de développement** : WAMP / VS Code

---

## 📁 Structure du projet

```
packajeux/
├── config/              # Configuration BDD (PDO)
├── database/            # Dump SQL
├── includes/            # Header / footer
├── public/              # Racine web
│   ├── assets/          # CSS, JS, images
│   ├── jeux/            # Pages des jeux
│   ├── jeux-quotidiens/ # Jeux bonus
│   ├── index.php
│   ├── login.php
│   └── register.php
├── src/                 # Classes PHP (évolution future)
├── .gitignore
├── LICENSE
└── README.md
```

---

## 🚀 Installation locale

### Prérequis

* WAMP / XAMPP / MAMP
* PHP 8+
* MySQL

### Installation

```bash
cd C:\wamp64\www
git clone https://github.com/Romain62300/packajeux.git
```

1. Créer une base de données `packajeux` via phpMyAdmin
2. Importer le fichier `database/packajeux.sql`
3. Vérifier la configuration dans `config/config.php`
4. Lancer le projet :

```text
http://localhost/packajeux/public/
```

---

## 📚 Ce que j’ai appris

Ce projet m’a permis de travailler sur :

* Structuration d’un projet PHP (séparation public / includes / config)
* Gestion des chemins sécurisés avec `__DIR__`
* Sécurisation des données (PDO, XSS, hash des mots de passe)
* Gestion des sessions et authentification
* Amélioration de l’expérience utilisateur (mode sombre, feedback visuel)
* Résolution de bugs liés aux environnements locaux (WAMP)
* Utilisation de Git (commits propres, gestion des fichiers)
* Implémentation d’algorithmes (minimax pour l’IA du morpion)

---

## 🗺️ Roadmap

* [ ] Finaliser le jeu de Belote (multijoueur)
* [ ] Migration vers InnoDB + relations SQL
* [ ] Ajout d’un leaderboard
* [ ] Déploiement en ligne
* [ ] Ajout de nouveaux jeux (Puissance 4, 2048…)
* [ ] Mise en place de tests
* [ ] Évolution vers une architecture MVC

---

## 📄 Licence

Projet sous licence **CC BY-NC 4.0**
Utilisation libre avec attribution, usage non commercial uniquement.

---

## 👤 Contact

**Romain Monier** — Développeur web
📍 Lens, Hauts-de-France

* 📧 [r.monier62@hotmail.com](mailto:r.monier62@hotmail.com)
* 💼 LinkedIn : https://www.linkedin.com/in/romainmonier
* 🐙 GitHub : https://github.com/Romain62300
* 🐛 Issues : https://github.com/Romain62300/packajeux/issues
