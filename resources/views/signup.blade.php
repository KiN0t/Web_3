@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<section>
    <h2>Inscription</h2>
    @if(session('success'))
        <div style="background:#e8f5e9;color:#388e3c;padding:1rem;border-radius:8px;margin-bottom:1rem;width:100%;max-width:500px;">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nom complet" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Mot de passe (min. 8 caractères)" required>
        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
        <button type="submit">S'inscrire</button>
        <a href="{{ route('login') }}" style="text-align:center;color:#6B46C1;">Déjà un compte ? Se connecter</a>
    </form>
</section>
@endsection