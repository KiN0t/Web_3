@extends('layouts.app')
@section('title', $projet->nom)
@section('content')
<section>
    <h2>{{ $projet->nom }}</h2>
    <div class="detail">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <span class="badge {{ $projet->statut }}">{{ ucfirst($projet->statut) }}</span>
            <a href="{{ route('projets.edit', $projet) }}" class="btn-details" style="background:#f57c00;">Modifier</a>
        </div>
        <p><strong>Client :</strong> {{ $projet->client }}</p>
        <p style="margin-top:0.5rem;">{{ $projet->description }}</p>

        <div style="display:flex;gap:2rem;margin-top:1.5rem;flex-wrap:wrap;">
            <div class="item" style="flex:1;min-width:150px;">
                <h3>Budget</h3>
                <p>{{ $projet->budget_heures }}h</p>
            </div>
            <div class="item" style="flex:1;min-width:150px;">
                <h3>Heures passées</h3>
                <p>{{ $projet->heuresTotales() }}h</p>
            </div>
            <div class="item" style="flex:1;min-width:150px;">
                <h3>Heures restantes</h3>
                <p style="color:{{ $projet->heuresRestantes() < 0 ? '#c62828' : '#388e3c' }}">
                    {{ $projet->heuresRestantes() }}h
                </p>
            </div>
        </div>

        <h3 style="margin-top:2rem;margin-bottom:1rem;">Tickets ({{ $projet->tickets->count() }})</h3>
        @forelse($projet->tickets as $ticket)
        <div class="ticket-item">
            <div class="ticket-header">
                <span class="ticket-title">{{ $ticket->titre }}</span>
                <span class="badge {{ $ticket->statut }}">{{ ucfirst($ticket->statut) }}</span>
            </div>
            <div class="ticket-meta">
                <span class="priority-badge priority-{{ $ticket->priorite }}">{{ ucfirst($ticket->priorite) }}</span>
                <span>{{ $ticket->heuresTotales() }}h / {{ $ticket->heures_estimees }}h estimées</span>
            </div>
            <div class="ticket-actions">
                <a href="{{ route('tickets.show', $ticket) }}" class="btn-details">Voir</a>
            </div>
        </div>
        @empty
        <p>Aucun ticket sur ce projet.</p>
        @endforelse

        <div style="margin-top:1.5rem;">
            <a href="{{ route('tickets.create') }}" ><button>+ Nouveau ticket</button></a>
            <a href="{{ route('projets.index') }}" class="btn-details" style="margin-left:0.5rem;">← Retour</a>
        </div>
    </div>
</section>
@endsection
