@extends('layouts.app')
@section('title', 'Projets')
@section('content')
<section>
    <h2>Projets</h2>
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('projets.create') }}"><button>+ Nouveau projet</button></a>
    </div>
    <div class="list">
        @forelse($projets as $projet)
        <div class="item">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <h3>{{ $projet->nom }}</h3>
                <span class="badge {{ $projet->statut }}">{{ ucfirst($projet->statut) }}</span>
            </div>
            <p style="color:#666;margin:0.5rem 0;">{{ $projet->client }}</p>
            <p>{{ $projet->description }}</p>
            <p style="margin-top:0.5rem;font-size:0.9rem;">
                Budget : <strong>{{ $projet->budget_heures }}h</strong> —
                Tickets : <strong>{{ $projet->tickets_count }}</strong>
            </p>
                <div style="display:flex;gap:0.5rem;margin-top:1rem;align-items:center;">
                    <a href="{{ route('projets.show', $projet) }}" class="btn-details">Voir</a>
                    <a href="{{ route('projets.edit', $projet) }}" class="btn-details" style="background:#f57c00;">Modifier</a>
                    <form action="{{ route('projets.destroy', $projet) }}" method="POST" onsubmit="return confirm('Supprimer ce projet ?')" style="margin:0;background:none;padding:0;box-shadow:none;border:none;">
                        @csrf @method('DELETE')
                        <button class="btn-delete">Supprimer</button>
                    </form>
                </div>
                
        </div>
        @empty
        <p>Aucun projet pour l'instant.</p>
        @endforelse
    </div>
</section>
@endsection
