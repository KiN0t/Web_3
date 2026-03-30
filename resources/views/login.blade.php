@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <section>
        <h2>Connexion</h2>
        <form action="{{ route('dashboard') }}" method="GET">
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
            <p style="text-align:center;margin-top:0.5rem;">
                <a href="{{ route('forgot-password') }}" style="color:#6B46C1;">Mot de passe oublié ?</a>
            </p>
            <p style="text-align:center;">
                Pas encore de compte ? <a href="{{ route('signup') }}" style="color:#6B46C1;">S'inscrire</a>
            </p>
        </form>
    </section>
@endsection
