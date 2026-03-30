@extends('layouts.app')
@section('title', 'Modifier ticket')
@section('content')
<section>
    <h2>Modifier : {{ $ticket->titre }}</h2>
    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
        @csrf @method('PUT')
        <input type="text" name="titre" value="{{ old('titre', $ticket->titre) }}" required>
        <textarea name="description" rows="3">{{ old('description', $ticket->description) }}</textarea>
        <select name="projet_id" required>
            @foreach($projets as $projet)
            <option value="{{ $projet->id }}" {{ $ticket->projet_id == $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
            @endforeach
        </select>
        <select name="statut">
            @foreach(['ouvert', 'en-cours', 'ferme'] as $s)
            <option value="{{ $s }}" {{ $ticket->statut === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="priorite">
            @foreach(['low', 'medium', 'high'] as $p)
            <option value="{{ $p }}" {{ $ticket->priorite === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
            @endforeach
        </select>
        <input type="number" name="heures_estimees" step="0.5" min="0" value="{{ old('heures_estimees', $ticket->heures_estimees) }}" required>
        <div class="checkbox-field">
            <input type="checkbox" name="facturable" id="facturable" value="1" {{ $ticket->facturable ? 'checked' : '' }}>
            <label for="facturable">Facturable</label>
        </div>
        <button type="submit">Enregistrer</button>
        <a href="{{ route('tickets.index') }}" style="text-align:center;color:#6B46C1;">Annuler</a>
    </form>
</section>
@endsection
