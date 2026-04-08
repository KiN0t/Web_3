<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Assure que la page est responsive et s'adapte à tous les types d'écrans, notamment les mobiles. -->
    <title>@yield('title', 'Site de Ticketing')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Inclus un token CSRF pour sécuriser les formulaires contre les attaques de type Cross-Site Request Forgery. Ce token doit être inclus dans tous les formulaires POST pour que Laravel puisse vérifier leur légitimité. -->
</head>
<body>
    <header>
        <h1>Site de Ticketing</h1>
        <nav>
            <div class="menu-toggle" onclick="toggleMenu()">
                <span></span><span></span><span></span>
            </div>
            @auth
            <ul id="nav-menu">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('projets.index') }}">Projets</a></li>
                <li><a href="{{ route('tickets.index') }}">Tickets</a></li>
                <li><a href="{{ route('profile') }}">Profil</a></li>
                <li>
                    <a href="#" onclick="document.getElementById('logout-form').submit()">Déconnexion</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </ul>
            @else
            <ul id="nav-menu">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li><a href="{{ route('login') }}">Connexion</a></li>
                <li><a href="{{ route('signup') }}">Inscription</a></li>
            </ul>
            @endauth
        </nav>
    </header>

    <main>
        @if(session('success'))
            <div style="background:#e8f5e9;color:#388e3c;padding:1rem;border-radius:8px;margin-bottom:1rem;max-width:800px;width:100%;">
                {{ session('success') }}
            </div>
        @endif
        @yield('content') <!-- Yield appelle la section content qui est définie dans les vues enfants, comme ici dans index.blade.php ou dashboard.blade.php. C'est là que le contenu spécifique à chaque page sera affiché. -->
    </main>

    <footer>
        <p>&copy; 2026 Site de Ticketing. Tous droits réservés.</p>
    </footer>

    <script>
        function toggleMenu() { <!-- Fonction pour basculer l'affichage du menu de navigation sur les petits écrans -->
            document.getElementById('nav-menu').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
