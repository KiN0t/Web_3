@extends('layouts.app')
@section('title', 'Tickets')
@section('content')
<section>
    <h2>Tickets</h2>
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('tickets.create') }}"><button>+ Nouveau ticket</button></a>
    </div>
    <div class="item" style="margin-bottom:1.5rem;">
        <h3>Ajouter un ticket rapidement</h3>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:1rem;">
            <input type="text" id="api-titre" placeholder="Titre" style="flex:2;min-width:150px;">
            <input type="text" id="api-description" placeholder="Description (optionnel)" style="flex:2;min-width:150px;">
            <select id="api-projet" style="flex:1;min-width:150px;padding:0.8rem;border:1px solid #e0e0e0;border-radius:6px;">
                @foreach(\App\Models\Projet::all() as $projet)
                <option value="{{ $projet->id }}">{{ $projet->nom }}</option>
                @endforeach
            </select>
            <select id="api-statut" style="flex:1;min-width:100px;padding:0.8rem;border:1px solid #e0e0e0;border-radius:6px;">
                <option value="ouvert">Ouvert</option>
                <option value="en-cours">En cours</option>
                <option value="ferme">Fermé</option>
            </select>
            <select id="api-priorite" style="flex:1;min-width:100px;padding:0.8rem;border:1px solid #e0e0e0;border-radius:6px;">
                <option value="low">Basse</option>
                <option value="medium" selected>Moyenne</option>
                <option value="high">Haute</option>
            </select>
            <input type="number" id="api-heures" placeholder="Heures estimées" step="0.5" min="0" style="flex:1;min-width:100px;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" id="api-facturable" checked>
                <label for="api-facturable">Facturable</label>
            </div>
            <button onclick="submitTicketApi()" style="width:auto;">+ Ajouter</button>
        </div>
        <div id="api-message" style="margin-top:0.5rem;color:#388e3c;display:none;">Ticket ajouté !</div>
    </div>

    <div id="tickets-list">
        @forelse($tickets as $ticket)
        @empty
        <p>Aucun ticket pour l'instant.</p>
        @endforelse
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

@push('scripts')
<script>

<!-- On génère le token côté serveur et on l injecte directement  -->
    const API_TOKEN = '{{ session("api_token") }}';


    async function submitTicketApi() {
        const titre = document.getElementById('api-titre').value;
        const description = document.getElementById('api-description').value;
        const projet_id = document.getElementById('api-projet').value;
        const statut = document.getElementById('api-statut').value;
        const priorite = document.getElementById('api-priorite').value;
        const heures_estimees = document.getElementById('api-heures').value;
        const facturable = document.getElementById('api-facturable').checked;

        if (!titre || !heures_estimees || !projet_id) {
            alert('Titre, projet et heures sont requis.');
            return;
        }

        const response = await fetch('/api/tickets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': 'Bearer ' + API_TOKEN
            },
            body: JSON.stringify({
                titre,
                description,
                projet_id,
                statut,
                priorite,
                heures_estimees,
                facturable
            })
        });

        const data = await response.json();

        if (response.ok) {
            document.getElementById('api-message').style.display = 'block';
            document.getElementById('api-titre').value = '';
            document.getElementById('api-description').value = '';
            document.getElementById('api-heures').value = '';

            const list = document.getElementById('tickets-list');
            const div = document.createElement('div');
            div.className = 'ticket-item';
            <!-- innerhtml c une propriété js qui permet dinsérer du contenu HTML à lintérieur dun élément.  -->
            div.innerHTML = ` 
                <div class="ticket-header">
                    <h3 class="ticket-title">${data.titre}</h3>
                    <span class="badge ${data.statut}">${data.statut}</span>
                </div>
                <div class="ticket-meta">
                    <span class="priority-badge priority-${data.priorite}">${data.priorite.charAt(0).toUpperCase() + data.priorite.slice(1)}</span>                    <span class="project-name">${data.projet?.nom ?? ''}</span>
                    <span>0h / ${data.heures_estimees}h</span>
                    ${data.facturable ? '<span class="badge actif" style="font-size:0.75rem;">Facturable</span>' : ''}
                </div>
                <p class="ticket-description">${data.description ?? ''}</p>
                <div style="display:flex;gap:0.5rem;margin-top:1rem;align-items:center;">
                    <a href="/tickets/${data.id}" class="btn-details">Voir</a>
                    <a href="/tickets/${data.id}/edit" class="btn-details" style="background:#f57c00;">Modifier</a>
                    <form action="/tickets/${data.id}" method="POST" onsubmit="return confirm('Supprimer ?')" style="margin:0;background:none;padding:0;box-shadow:none;border:none;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button style="width:auto;padding:0.5rem 1rem;background:#d32f2f;color:white;border:none;border-radius:4px;cursor:pointer;font-size:0.9rem;">Supprimer</button>                    </form>
                </div>
            `;
            list.prepend(div);

            setTimeout(() => document.getElementById('api-message').style.display = 'none', 3000);
        } else {
            alert('Erreur : ' + (data.message ?? JSON.stringify(data.errors)));
        }
    }
</script>
@endpush
