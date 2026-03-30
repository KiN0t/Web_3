<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Site de Ticketing')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Site de Ticketing. Tous droits réservés.</p>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('nav-menu').classList.toggle('show');
        }
    </script>
</body>
</html>
