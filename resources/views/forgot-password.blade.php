@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
    <section>
        <h2>Réinitialiser le mot de passe</h2>
        <form action="{{ route('forgot-password') }}" method="GET">
            <input type="email" placeholder="Votre adresse email" required>
            <button type="submit">Envoyer le lien</button>
            <p style="text-align:center;margin-top:0.5rem;">
                <a href="{{ route('login') }}" style="color:#6B46C1;">Retour à la connexion</a>
            </p>
        </form>
    </section>
@endsection
