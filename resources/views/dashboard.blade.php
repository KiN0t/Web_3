@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')

@if(auth()->user()->role === 'admin')
<div class="admin-banner" style="background:#6B46C1;color:white;padding:0.75rem 1.5rem;display:flex;justify-content:space-between;align-items:center;border-radius:8px;margin-bottom:1.5rem;width:100%;max-width:600px;">
    <span>Administration</span>
    <a href="{{ route('admin.index') }}" style="color:white;text-decoration:underline;">Accéder au panel admin</a>
</div>
@endif

<section>
    <h2>Tableau de bord</h2>
    <p>Bienvenue, {{ auth()->user()->name }} !</p>

    <div class="list" style="margin-top:1.5rem;">
        <div class="item">
            <h3>Projets</h3>
            <p>{{ $totalProjets }} projet(s) accessible(s).</p>
            <a href="{{ route('projets.index') }}">Voir tous</a>
        </div>
        <div class="item">
            <h3>Tickets</h3>
            <p>{{ $totalTickets }} ticket(s) accessible(s).</p>
            <a href="{{ route('tickets.index') }}">Voir tous</a>
        </div>
    </div>

    <h3 style="color:#6B46C1;margin-top:2rem;margin-bottom:1rem;">Statistiques</h3>
    <div class="list">
        <div class="item">
            <h3>Tickets ouverts</h3>
            <p>{{ $ticketsOuverts }}</p>
        </div>
        <div class="item">
            <h3>Tickets en cours</h3>
            <p>{{ $ticketsEnCours }}</p>
        </div>
        <div class="item">
            <h3>Tickets fermés</h3>
            <p>{{ $ticketsFermes }}</p>
        </div>
        <div class="item">
            <h3>Heures totales passées</h3>
            <p>{{ $heuresTotales }}h</p>
        </div>
        <div class="item">
            <h3>Heures facturables</h3>
            <p>{{ $heuresFacturables }}h</p>
        </div>
    </div>
</section>
@endsection