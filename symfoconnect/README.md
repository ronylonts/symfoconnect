# SymfoConnect - Evaluation Jour 1
Nom : Roland lontsie 

# Évaluation Jour 1 — Fondations de SymfoConnect

**Durée :** 3h (14h00 – 17h00) | **Barème :** /20 | **Mode :** Individuel ou binôme

---

## 🌐 Le Projet : SymfoConnect

SymfoConnect est un réseau social développé progressivement sur 3 jours. À l'issue de la formation, vous aurez une application Symfony 7 complète et fonctionnelle.

### Vision globale sur les 3 jours

| Jour | Ce qui est construit |
|------|---------------------|
| **Jour 1** | Base du projet, entités, pages publiques, formulaire de post |
| **Jour 2** | Authentification, follows, likes, fil d'actualité, sécurité |
| **Jour 3** | Messagerie privée, API, cache, tests, déploiement |

### Schéma de base de données final (pour information)

```
users ──< posts ──< likes (ManyToMany users)
  │
  └──< user_follows (ManyToMany self)
  └──< messages (sender / recipient)
  └──< notifications (recipient)
```

---

## 🎯 Objectifs Fonctionnels

À la fin de cette évaluation, l'application doit :

1. **Démarrer sans erreur** — le projet Symfony 7 est installé, la base de données est configurée et les migrations sont exécutées.

2. **Afficher une page d'accueil** (`/`) — liste des 10 derniers posts, triés par date décroissante, avec le nom de l'auteur et la date de publication.

3. **Afficher une page de profil** (`/profil/{username}`) — présente les informations de l'utilisateur (username, bio, avatar si renseigné) ainsi que ses posts. Retourne une erreur 404 si l'utilisateur n'existe pas.

4. **Permettre la création d'un post** (`/post/nouveau`) — formulaire avec validation (contenu obligatoire, longueur minimale). Après soumission valide, le post est sauvegardé, un message flash confirme la création et l'utilisateur est redirigé vers l'accueil.

5. **Avoir un layout cohérent** — toutes les pages héritent d'un template de base avec navigation et zone d'affichage des messages flash.

### Entités attendues

**User** — id, email (unique), username (unique), password, bio (nullable), avatarUrl (nullable), createdAt

**Post** — id, content, createdAt, author (ManyToOne → User)

---

## 📊 Barème (20 points)

| Critère | Points |
|---------|--------|
| Projet installé, BDD configurée, migrations sans erreur | 3 |
| Entité User : tous les champs, types corrects | 2 |
| Entité Post + relation ManyToOne vers User correcte | 2 |
| Page d'accueil fonctionnelle (liste, tri, auteur, date) | 4 |
| Page de profil fonctionnelle (infos user, ses posts, 404) | 3 |
| Formulaire de post avec validation, flash et redirection | 4 |
| Qualité du code (lisibilité, conventions PSR-12) | 2 |

---

## 📦 Livrable

Dossier du projet zippé ou lien vers dépôt Git.

---

*Bon courage ! 💪*


# Évaluation Jour 2 — Fonctionnalités Sociales de SymfoConnect

**Durée :** 3h (14h00 – 17h00) | **Barème :** /20 | **Pré-requis :** Évaluation Jour 1 complétée

---

## 🎯 Objectifs Fonctionnels

En continuant le projet SymfoConnect du Jour 1, l'application doit désormais :

1. **Inscription** — un visiteur peut créer un compte en renseignant son email, son username et un mot de passe. Le mot de passe est stocké de façon sécurisée (hashé). L'email et le username doivent être uniques.

2. **Connexion / Déconnexion** — un utilisateur peut se connecter avec son email et son mot de passe, puis se déconnecter. Le layout affiche son username quand il est connecté.

3. **Création de post sécurisée** — seul un utilisateur connecté peut créer un post. L'auteur est automatiquement l'utilisateur connecté.

4. **Suivre / Ne plus suivre** — un utilisateur connecté peut suivre ou ne plus suivre un autre utilisateur depuis sa page de profil. Il ne peut pas se suivre lui-même. La page de profil affiche le nombre de followers et de follows, ainsi que le bouton d'action correspondant à l'état actuel.

5. **Liker / Ne plus liker** — un utilisateur connecté peut liker ou retirer son like sur un post. Le nombre de likes est affiché sur chaque post.

6. **Fil d'actualité** (`/feed`) — accessible uniquement aux utilisateurs connectés. Affiche les posts des personnes que l'utilisateur suit, triés par date décroissante. Si l'utilisateur ne suit personne, un message l'invite à commencer à suivre des gens.

7. **Suppression sécurisée** — seul l'auteur d'un post peut le supprimer (contrôle via un Voter). Un autre utilisateur qui tente de supprimer un post qu'il ne possède pas reçoit une erreur 403.

8. **Notification de follow** — quand un utilisateur en suit un autre, une `Notification` est créée en base de données pour l'utilisateur suivi (type, contenu, destinataire, date).

### Nouvelles entités attendues

**Follow** — géré par une relation ManyToMany auto-référencée sur User (table `user_follows`)

**Like** — géré par une relation ManyToMany entre Post et User (table `post_likes`)

**Notification** — id, recipient (ManyToOne → User), type, content, isRead (défaut false), createdAt

---

## 📊 Barème (20 points)

| Critère | Points |
|---------|--------|
| Inscription fonctionnelle (validation, unicité, hash mdp) | 3 |
| Connexion / déconnexion + layout adapté | 2 |
| Création de post réservée aux connectés (auteur = user courant) | 1 |
| Follow / unfollow (compteurs, protection anti-auto-follow) | 3 |
| Like / unlike (affichage compteur et état) | 2 |
| Fil d'actualité fonctionnel et protégé | 3 |
| PostVoter : suppression réservée à l'auteur (403 sinon) | 3 |
| Notification créée en BDD lors d'un follow | 3 |

---

## 📦 Livrable

Dossier du projet zippé ou lien vers dépôt Git.

---

*Bon courage ! 🔐*


