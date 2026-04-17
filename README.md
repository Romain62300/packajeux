# Packajeux 🎮

> Site de mini-jeux en ligne gratuit, sans publicité, développé en PHP/MySQL.

[![GitHub repo size](https://img.shields.io/github/repo-size/Romain62300/packajeux)](https://github.com/Romain62300/packajeux)
[![GitHub last commit](https://img.shields.io/github/last-commit/Romain62300/packajeux)](https://github.com/Romain62300/packajeux/commits/main)
[![License: CC BY-NC 4.0](https://img.shields.io/badge/License-CC%20BY--NC%204.0-lightgrey.svg)](https://creativecommons.org/licenses/by-nc/4.0/)

![Aperçu de Packajeux](./public/assets/images/apercu-Packajeux.png)

---

## 📋 À propos

**Packajeux** est mon projet personnel de développement web full-stack, commencé en avril 2025. C'est une plateforme de mini-jeux jouables directement dans le navigateur, conçue comme terrain d'apprentissage pour construire un projet complet (front, back, base de données, déploiement).

Développé par **Romain Monier** sous le statut auto-entrepreneur **Alakachan Dev**.

---

## ✨ Fonctionnalités

### 🎮 Jeux fonctionnels

| Jeu | Description | Statut |
|-----|-------------|--------|
| **Morpion** | 3×3 classique avec mode 2 joueurs et IA minimax | ✅ |
| **Pierre-Feuille-Ciseaux** | Contre l'ordinateur avec score | ✅ |
| **Devine le nombre** | Nombre mystère entre 1 et 100 | ✅ |
| **Jeu de Mémoire** | 4×4 cartes avec compteur de temps et coups | ✅ |
| **Belote** | Jeu de cartes à 4 joueurs | 🚧 En développement |

### 🛠️ Fonctionnalités transverses

- 👤 **Authentification** : inscription, connexion, sessions
- 🌓 **Mode sombre** avec persistance
- 📱 **Responsive** mobile et desktop
- 🪙 **Système de jetons** virtuels
- 🎨 **Design cohérent** avec logo custom SVG
- ⚖️ **Pages légales** : mentions, CGU, confidentialité

---

## 🔧 Stack technique

- **Langage** : PHP 8+ (sans framework)
- **Base de données** : MySQL 8+
- **Frontend** : HTML5, CSS3, JavaScript vanilla
- **Sécurité** : PDO avec requêtes préparées, `password_hash()`, `htmlspecialchars()`
- **Versioning** : Git / GitHub
- **Environnement de dev** : WAMP / VS Code

---

## 📁 Structure du projet

```
packajeux/
├── config/              # Configuration PDO / BDD
├── database/            # Dump SQL pour import
├── includes/            # Header et footer partagés
├── public/              # Racine web
│   ├── assets/          # CSS, JS, images
│   ├── jeux/            # Pages des jeux
│   ├── jeux-quotidiens/ # Jeux bonus quotidiens
│   ├── index.php
│   ├── login.php
│   └── register.php
├── src/                 # Classes PHP (à venir)
├── .gitignore
├── LICENSE
└── README.md
```

## 🚀 Installation locale

### Prérequis
- WAMP / XAMPP / MAMP
- PHP 8.0+
- MySQL 8+

### Étapes

1. Cloner le repo dans le dossier `www` de WAMP :
```bash
cd C:\wamp64\www
git clone https://github.com/Romain62300/packajeux.git
```

2. Importer la base de données :
   - Ouvrir phpMyAdmin
   - Créer une base `packajeux`
   - Importer `database/packajeux.sql`

3. Vérifier la configuration dans `config/config.php` (valeurs par défaut OK pour WAMP local).

4. Accéder au site : `http://localhost/packajeux/public/`

---

## 📚 Ce que j'ai appris en le développant

- **Architecture** : séparation public/config/includes, chemins relatifs sécurisés avec `__DIR__`
- **Sécurité** : PDO en mode exception, requêtes préparées, hachage bcrypt des mots de passe, protection XSS
- **Session & authentification** : gestion propre des sessions PHP, cookies "remember me"
- **UX** : mode sombre avec persistance, formulaires accessibles, feedback visuel
- **Debug** : résolution de bugs de chemins entre environnements (WAMP, serveur)
- **Git** : commits atomiques, `.gitignore`, `git rm --cached`, récupération de fichiers supprimés
- **Algos** : implémentation minimax pour l'IA du morpion

---

## 🤖 Utilisation d'outils IA

Pour les aspects complexes (debug, architecture, bonnes pratiques), je m'appuie sur des assistants IA (Claude, Copilot) comme le font la majorité des développeurs modernes. **Tout le code est compris, validé et maintenu par mes soins** — je peux expliquer chaque partie du projet.

---

## 🗺️ Roadmap

- [ ] Finir le jeu de Belote (multijoueur en temps réel)
- [ ] Migrer les tables MyISAM vers InnoDB + clés étrangères
- [ ] Ajouter un leaderboard global
- [ ] Mise en ligne sur un hébergeur pro
- [ ] Ajouter 2-3 nouveaux jeux (Puissance 4, 2048, Pendu)
- [ ] Ajouter des tests automatisés
- [ ] Migration progressive vers une architecture MVC

---

## 📄 Licence

Ce projet est sous licence **Creative Commons Attribution - Pas d'Utilisation Commerciale 4.0 International (CC BY-NC 4.0)**.

Vous pouvez **partager et adapter** ce projet à condition de **créditer l'auteur** et de **ne pas l'utiliser commercialement**.

[Voir la licence complète](https://creativecommons.org/licenses/by-nc/4.0/) • [Version française](./LICENSE-fr.txt)

---

## 👤 Contact

**Romain Monier** — Développeur web junior
📍 Lens, Hauts-de-France
🏢 Auto-entrepreneur Alakachan Dev

- 📧 [r.monier62@hotmail.com](mailto:r.monier62@hotmail.com)
- 💼 [LinkedIn](https://www.linkedin.com/in/romainmonier)
- 🐙 [GitHub](https://github.com/Romain62300)
- 🐛 [Signaler un bug](https://github.com/Romain62300/packajeux/issues)
