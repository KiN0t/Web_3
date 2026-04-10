# 🎫 Site de Ticketing — README

Application de gestion de tickets et projets développée avec **Laravel 12**, **Breeze** (auth), **Sanctum** (API) et **SQLite**.

---

## 🚀 Lancer le projet

```bash
# Installer les dépendances
composer install
npm install

# Créer la base de données et insérer les données de test
php artisan migrate:fresh --seed

# Dans deux terminaux séparés :
php artisan serve     # → http://localhost:8000
npm run dev           # → compile le CSS/JS (optionnel si tu utilises style.css direct)
```

**Compte de test :**
- Email : `admin@admin.com`
- Mot de passe : `password`

---

## 🗂️ Architecture du projet

```
app-web-trois/
│
├── app/                          ← Logique PHP de l'application
│   ├── Http/
│   │   ├── Controllers/          ← CONTROLLERS : reçoivent les requêtes, interrogent les modèles, renvoient les vues
│   │   │   ├── PageController.php          → Pages générales (home, dashboard, profil, inscription)
│   │   │   ├── ProjetController.php        → CRUD des projets (liste, créer, modifier, supprimer)
│   │   │   ├── TicketController.php        → CRUD des tickets + génération du token API
│   │   │   ├── TempsPasseController.php    → Ajout/suppression de temps passé sur un ticket
│   │   │   ├── AdminController.php         → Gestion des users, rôles, collaborateurs (panel admin)
│   │   │   └── Api/                        ← CONTROLLERS API : retournent du JSON (pas du HTML)
│   │   │       ├── AuthApiController.php   → Login/logout/forgot-password via API
│   │   │       └── TicketApiController.php → GET et POST tickets via API (consommé par le JS)
│   │   └── Middleware/
│   │       └── CheckRole.php     ← MIDDLEWARE : bloque l'accès aux routes selon le rôle (admin/collaborateur/client)
│   │
│   └── Models/                   ← MODÈLES : représentent les tables DB et leurs relations
│       ├── User.php              → Table users — relations : projets, tickets, projetsCollaborateur
│       ├── Projet.php            → Table projets — relations : tickets, collaborateurs, user
│       ├── Ticket.php            → Table tickets — relations : projet, tempsPasses, user — calculs : heuresRestantes, heuresFacturables
│       └── TempsPasse.php        → Table temps_passes — relations : ticket, user
│
├── database/
│   ├── migrations/               ← MIGRATIONS : définissent la structure des tables SQL
│   │   ├── create_users_table              → id, name, email, password, role
│   │   ├── create_projets_table            → id, nom, client, statut, budget_heures, user_id
│   │   ├── create_tickets_table            → id, titre, statut, priorite, heures_estimees, facturable, projet_id, user_id
│   │   ├── create_temps_passes_table       → id, heures, commentaire, date, ticket_id, user_id
│   │   ├── create_projet_collaborateurs_table → liaison many-to-many projets ↔ users
│   │   └── add_role_to_users_table         → ajout colonne role sur users
│   │
│   └── seeders/
│       └── DatabaseSeeder.php    ← SEEDER : insère les données de test (1 admin, 3 projets, 9 tickets, 18 temps passés)
│
├── resources/
│   └── views/                    ← VUES BLADE : le HTML affiché à l'utilisateur
│       ├── layouts/
│       │   └── app.blade.php     → Template principal : header, nav, footer — toutes les pages l'étendent avec @extends
│       ├── projets/
│       │   ├── index.blade.php   → Liste des projets
│       │   ├── show.blade.php    → Détail d'un projet + ses tickets
│       │   ├── create.blade.php  → Formulaire création projet
│       │   └── edit.blade.php    → Formulaire modification projet
│       ├── tickets/
│       │   ├── index.blade.php   → Liste des tickets + formulaire ajout rapide via API (fetch JS)
│       │   ├── show.blade.php    → Détail ticket + stats heures + gestion temps passé
│       │   ├── create.blade.php  → Formulaire création ticket
│       │   └── edit.blade.php    → Formulaire modification ticket
│       ├── admin/
│       │   └── index.blade.php   → Panel admin : gestion users, rôles, collaborateurs par projet
│       ├── dashboard.blade.php   → Tableau de bord avec stats réelles selon le rôle
│       ├── home.blade.php        → Page d'accueil publique
│       ├── login.blade.php       → Page de connexion (Breeze)
│       ├── signup.blade.php      → Inscription
│       └── forgot-password.blade.php → Mot de passe oublié
│
├── routes/
│   ├── web.php                   ← ROUTES WEB : URLs → Controllers (retournent du HTML)
│   └── api.php                   ← ROUTES API : URLs /api/... → Controllers API (retournent du JSON)
│
└── public/
    └── css/
        └── style.css             ← CSS global du site
```

---

## 🗄️ Base de données

### Tables et relations

```
users ──────────────────────────────────────────────────────────┐
  id, name, email, password, role (admin/collaborateur/client)  │
  │                                                              │
  │ hasMany                                                      │
  ▼                                                              │
projets ────────────────────────────────────────────────────────┤
  id, nom, client, statut, budget_heures, user_id               │
  │                                                              │
  │ hasMany                           belongsToMany (via projet_collaborateurs)
  ▼                                                              │
tickets ◄───────────────────────────────────────────────────────┘
  id, titre, statut, priorite, heures_estimees, facturable, projet_id, user_id
  │
  │ hasMany
  ▼
temps_passes
  id, heures, commentaire, date, ticket_id, user_id
```

### Commandes utiles

```bash
php artisan migrate:fresh --seed   # Recrée toutes les tables + données de test
php artisan tinker                 # Console interactive pour tester des requêtes Eloquent
php artisan route:list             # Liste toutes les routes disponibles
```

---

## 👥 Rôles utilisateurs

| Rôle | Accès projets | Accès tickets | Panel admin |
|------|--------------|---------------|-------------|
| **Admin** | Tout voir, créer, modifier, supprimer | Tout voir, créer, modifier, supprimer | ✅ Oui |
| **Collaborateur** | Voir uniquement ses projets liés | Créer/modifier sur ses projets, pas supprimer | ❌ Non |
| **Client** | Créer et voir ses propres projets | Voir seulement | ❌ Non |

Le middleware `CheckRole` bloque les routes selon le rôle. Il est appliqué dans `routes/web.php` via `Route::middleware('role:admin')`.

---

## 🌐 API REST

L'API utilise **Sanctum** pour l'authentification par token.

### Endpoints disponibles

| Méthode | URL | Description | Auth |
|---------|-----|-------------|------|
| POST | `/api/login` | Connexion, retourne un token | ❌ |
| POST | `/api/logout` | Déconnexion, supprime le token | ✅ |
| POST | `/api/forgot-password` | Simule l'envoi d'un email | ❌ |
| GET | `/api/tickets` | Liste les tickets selon le rôle | ✅ |
| POST | `/api/tickets` | Crée un ticket | ✅ |
| GET | `/api/tickets/{id}` | Détail d'un ticket | ✅ |


---



