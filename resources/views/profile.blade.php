@extends('layouts.app')
@section('title', 'Profil')
@section('content')
<section>
    <h2>Profil</h2>
    <div class="detail">
        <p><strong>Nom :</strong> {{ auth()->user()->name }}</p>
        <p style="margin-top:0.75rem;"><strong>Email :</strong> {{ auth()->user()->email }}</p>
        <p style="margin-top:0.75rem;"><strong>Membre depuis :</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
        <p style="margin-top:0.75rem;"><strong>Rôle :</strong> {{ auth()->user()->role ?? 'collaborateur' }}</p>
    </div>
</section>
@endsection