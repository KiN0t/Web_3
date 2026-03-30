@extends('layouts.app')
@section('title', $ticket->titre)
@section('content')
<section>
    <h2>{{ $ticket->titre }}</h2>
    <div class="detail">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <span class="badge {{ $ticket->statut }}">{{ ucfirst($ticket->statut) }}</span>
            <span class="priority-badge priority-{{ $ticket->priorite }}">{{ ucfirst($ticket->priorite) }}</span>
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn-details" style="background:#f57c00;">Modifier</a>
        </div>

        <p><strong>Projet :</strong> <a href="{{ route('projets.show', $ticket->projet) }}" style="color:#6B46C1;">{{ $ticket->projet->nom }}</a></p>
        <p style="margin-top:0.5rem;">{{ $ticket->description }}</p>
        @if($ticket->facturable)
            <span class="badge actif" style="margin-top:0.5rem;display:inline-block;">Facturable</span>
        @endif

        {{-- Stats heures --}}
        <div style="display:flex;gap:1.5rem;margin-top:1.5rem;flex-wrap:wrap;">
            <div class="item" style="flex:1;min-width:120px;">
                <h3>Estimées</h3>
                <p>{{ $ticket->heures_estimees }}h</p>
            </div>
            <div class="item" style="flex:1;min-width:120px;">
                <h3>Passées</h3>
                <p>{{ $ticket->heuresTotales() }}h</p>
            </div>
            <div class="item" style="flex:1;min-width:120px;">
                <h3>Restantes</h3>
                <p style="color:{{ $ticket->heuresRestantes() < 0 ? '#c62828' : '#388e3c' }}">
                    {{ $ticket->heuresRestantes() }}h
                </p>
            </div>
            <div class="item" style="flex:1;min-width:120px;">
                <h3>Facturables</h3>
                <p>{{ $ticket->heuresFacturables() }}h</p>
            </div>
        </div>

        {{-- Formulaire ajout temps --}}
        <h3 style="margin-top:2rem;margin-bottom:1rem;">Ajouter du temps</h3>
        <form action="{{ route('temps.store', $ticket) }}" method="POST" style="max-width:100%;">
            @csrf
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <input type="number" name="heures" placeholder="Heures" step="0.25" min="0.25" required style="flex:1;min-width:100px;">
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required style="flex:1;min-width:150px;">
                <input type="text" name="commentaire" placeholder="Commentaire (optionnel)" style="flex:2;min-width:200px;">
            </div>
            <button type="submit" style="margin-top:1rem;">Ajouter</button>
        </form>

        {{-- Liste temps passés --}}
        <h3 style="margin-top:2rem;margin-bottom:1rem;">Temps enregistrés</h3>
        <div style="width:100%;">
            @forelse($ticket->tempsPasses as $temps)
            <div style="display:flex;align-items:center;padding:0.5rem 1rem;border-bottom:1px solid #e0e0e0;">
                
                <!-- Contenu -->
                <div style="
                    display:flex;
                    align-items:center;
                    gap:0.75rem;
                    flex:1;
                    min-width:0;
                    flex-wrap:wrap;
                ">
                    <strong>{{ $temps->heures }}h</strong>
                    <span style="color:#888;">—</span>
                    <span style="color:#666;">{{ \Carbon\Carbon::parse($temps->date)->format('d/m/Y') }}</span>
                    @if($temps->commentaire)
                        <span style="
                            color:#888;
                            font-style:italic;
                            white-space:normal;
                            overflow:visible;
                            text-overflow:unset;
                            word-break:break-word;
                        ">
                            {{ $temps->commentaire }}
                        </span>
                    @endif
                </div>

                <!-- Bouton -->
                <form action="{{ route('temps.destroy', $temps) }}" method="POST"
                    onsubmit="return confirm('Supprimer ?')"
                    style="margin:0;background:none;padding:0;box-shadow:none;border:none;flex-shrink:0;display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn-delete no-full" style="
                        display:inline-block;
                        width:auto !important;
                        padding:0.25rem 0.6rem;
                        font-size:0.75rem;
                        border:none;
                        box-shadow:none;
                    ">
                        Supprimer
                    </button>
                </form>

            </div>
            @empty
            <p style="color:#666;">Aucun temps enregistré.</p>
            @endforelse
        </div>

        <div style="margin-top:1.5rem;">
            <a href="{{ route('tickets.index') }}" class="btn-details">← Retour aux tickets</a>
        </div>
    </div>
</section>
@endsection
