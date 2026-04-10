@extends('layouts.app')
@section('title', 'Administration')
@section('content') <!--Créé une section qui sera appelé par un yield dans un layout, ici le layout app.blade.php. Le titre de la page est défini à "Administration". -->
<section style="max-width:1000px;width:100%;">
    <h2>Administration</h2>

    {{-- GESTION UTILISATEURS --}}
    <h3 style="color:#6B46C1;margin-bottom:1rem;">Utilisateurs</h3>
    @foreach($users as $user)
    <div class="item" style="margin-bottom:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <div>
                <strong>{{ $user->name }}</strong>
                <span style="color:#666;margin-left:0.5rem;">{{ $user->email }}</span>
                <span class="badge actif" style="margin-left:0.5rem;">{{ $user->role }}</span>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                {{-- Changer le rôle --}}
                <form action="{{ route('admin.updateRole', $user) }}" method="POST" style="margin:0;background:none;padding:0;box-shadow:none;border:none;display:flex;gap:0.5rem;">
                    @csrf @method('PATCH')
                    <select name="role" style="padding:0.4rem;border-radius:4px;border:1px solid #e0e0e0;">
                        @foreach(['admin', 'collaborateur', 'client'] as $role)
                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    <button style="width:auto;padding:0.4rem 0.75rem;background:#6B46C1;color:white;border:none;border-radius:4px;cursor:pointer;">Changer</button>
                </form>

                {{-- Supprimer --}}
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.deleteUser', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')" style="margin:0;background:none;padding:0;box-shadow:none;border:none;">
                    @csrf @method('DELETE')
                    <button style="width:auto;padding:0.4rem 0.75rem;background:#d32f2f;color:white;border:none;border-radius:4px;cursor:pointer;">Supprimer</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Modifier nom/email --}}
        <form action="{{ route('admin.updateUser', $user) }}" method="POST" style="margin-top:1rem;background:none;padding:0;box-shadow:none;border:none;display:flex;gap:0.5rem;flex-wrap:wrap;">
            @csrf @method('PATCH')
            <input type="text" name="name" value="{{ $user->name }}" style="flex:1;min-width:150px;padding:0.4rem;border-radius:4px;border:1px solid #e0e0e0;">
            <input type="email" name="email" value="{{ $user->email }}" style="flex:2;min-width:200px;padding:0.4rem;border-radius:4px;border:1px solid #e0e0e0;">
            <button style="width:auto;padding:0.4rem 0.75rem;background:#f57c00;color:white;border:none;border-radius:4px;cursor:pointer;">Modifier</button>
        </form>
    </div>
    @endforeach

    {{-- GESTION COLLABORATEURS PAR PROJET --}}
    <h3 style="color:#6B46C1;margin-top:2rem;margin-bottom:1rem;">Collaborateurs par projet</h3>
    @foreach($projets as $projet)
    <div class="item" style="margin-bottom:1rem;">
        <strong>{{ $projet->nom }}</strong>
        <span style="color:#666;margin-left:0.5rem;">{{ $projet->client }}</span>

        {{-- Collaborateurs actuels --}}
        <div style="margin-top:0.75rem;display:flex;flex-wrap:wrap;gap:0.5rem;">
            @forelse($projet->collaborateurs as $collab)
            <div style="display:flex;align-items:center;gap:0.5rem;background:#f5f5f5;padding:0.25rem 0.75rem;border-radius:20px;">
                <span>{{ $collab->name }}</span>
                <form action="{{ route('admin.removeCollaborateur', [$projet, $collab]) }}" method="POST" style="margin:0;background:none;padding:0;box-shadow:none;border:none;">
                    @csrf @method('DELETE')
                    <button style="width:auto;background:none;color:#d32f2f;border:none;cursor:pointer;font-size:0.9rem;padding:0;">✕</button>
                </form>
            </div>
            @empty
            <span style="color:#888;">Aucun collaborateur</span>
            @endforelse
        </div>

        {{-- Ajouter collaborateur --}}
        <form action="{{ route('admin.addCollaborateur', $projet) }}" method="POST" style="margin-top:0.75rem;background:none;padding:0;box-shadow:none;border:none;display:flex;gap:0.5rem;">
            @csrf
            <select name="user_id" style="flex:1;padding:0.4rem;border-radius:4px;border:1px solid #e0e0e0;">
                <option value="">-- Ajouter un collaborateur --</option>
                @foreach($users->where('role', 'collaborateur') as $collab)
                <option value="{{ $collab->id }}">{{ $collab->name }}</option>
                @endforeach
            </select>
            <button style="width:auto;padding:0.4rem 0.75rem;background:#6B46C1;color:white;border:none;border-radius:4px;cursor:pointer;">Ajouter</button>
        </form>
    </div>
    @endforeach
</section>
@endsection

@push('scripts') <!-- Mets le script dans une pile de scripts qui sera appelé par un stack dans le layout, ici app.blade.php. Cela permet d'ajouter des scripts spécifiques à certaines pages sans les inclure dans le layout global. -->
<script>
    <!-- Récupère le token Sanctum depuis la session Laravel -->
    const API_TOKEN = '{{ auth()->user()->currentAccessToken()?->token ?? "" }}';

    <!-- Fonction pour charger les tickets via l'API -->
    async function loadTicketsFromApi() {
        try {
            const response = await fetch('/api/tickets', { <!-- Requete http -->
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN,
                    'Accept': 'application/json', <!-- Reponse en JSON -->
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content <!-- Portection CSRF pour les requetes POST/PUT/PATCH/DELETE -->
                }
            });
            const tickets = await response.json(); <!-- Convertit la reponse en JSON -->
            console.log('Tickets depuis API:', tickets);
        } catch (error) {
            console.error('Erreur API:', error);
        }
    }

    <!-- Fonction pour créer un ticket via l'API sans rechargement -->
    async function createTicketApi(data) { <!--Vive l'async -->
        const response = await fetch('/api/tickets', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + API_TOKEN,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    }
</script>
@endpush