@extends('layouts.app')
@section('title', 'Tickets')
@section('content')
<section>
    <h2>Tickets</h2>
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('tickets.create') }}"><button>+ Nouveau ticket</button></a>
    </div>
    <div class="list">
        @forelse($tickets as $ticket)
        <div class="ticket-item">
            <div class="ticket-header">
                <h3 class="ticket-title">{{ $ticket->titre }}</h3>
                <span class="badge {{ $ticket->statut }}">{{ ucfirst($ticket->statut) }}</span>
            </div>
            <div class="ticket-meta">
                <span class="priority-badge priority-{{ $ticket->priorite }}">{{ ucfirst($ticket->priorite) }}</span>
                <span class="project-name">{{ $ticket->projet->nom }}</span>
                <span>{{ $ticket->heuresTotales() }}h / {{ $ticket->heures_estimees }}h</span>
                @if($ticket->facturable)
                <span class="badge actif" style="font-size:0.75rem;">Facturable</span>
                @endif
            </div>
            <p class="ticket-description">{{ $ticket->description }}</p>
                <div style="display:flex;gap:0.5rem;margin-top:1rem;align-items:center;">
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn-details">Voir</a>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn-details" style="background:#f57c00;">Modifier</a>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer ?')" style="margin:0;background:none;padding:0;box-shadow:none;border:none;">
                        @csrf @method('DELETE')
                        <button class="btn-delete">Supprimer</button>
                    </form>
                </div>
            </p>
        </div>
        @empty
        <p>Aucun ticket pour l'instant.</p>
        @endforelse
    </div>
</section>
@endsection
