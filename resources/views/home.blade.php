@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <section>
        <h2>Bienvenue sur notre site de ticketing</h2>
        <p>Gérez vos projets et tickets efficacement. Connectez-vous pour commencer.</p>
        <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:center;">
            <a href="{{ route('login') }}"><button>Se connecter</button></a>
            <a href="{{ route('signup') }}"><button style="background:white;color:var(--accent-color, #6B46C1);border:2px solid var(--accent-color, #6B46C1);">S'inscrire</button></a>
        </div>
    </section>
@endsection
