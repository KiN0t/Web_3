@extends('layouts.app')
@section('title', 'Nouveau ticket')
@section('content')
<section>
    <h2>Nouveau ticket</h2>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <input type="text" name="titre" placeholder="Titre du ticket" value="{{ old('titre') }}" required>
        <textarea name="description" placeholder="Description" rows="3">{{ old('description') }}</textarea>
        <select name="projet_id" required>
            <option value="">-- Choisir un projet --</option>
            @foreach($projets as $projet)
            <option value="{{ $projet->id }}" {{ old('projet_id') == $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
            @endforeach
        </select>
        <select name="statut">
            <option value="ouvert">Ouvert</option>
            <option value="en-cours">En cours</option>
            <option value="ferme">Fermé</option>
        </select>
        <select name="priorite">
            <option value="low">Basse</option>
            <option value="medium" selected>Moyenne</option>
            <option value="high">Haute</option>
        </select>
        <input type="number" name="heures_estimees" placeholder="Heures estimées" step="0.5" min="0" value="{{ old('heures_estimees') }}" required>
        <div class="checkbox-field">
            <input type="checkbox" name="facturable" id="facturable" value="1" {{ old('facturable') ? 'checked' : '' }}>
            <label for="facturable">Facturable</label>
        </div>
        <button type="submit">Créer le ticket</button>
        <a href="{{ route('tickets.index') }}" style="text-align:center;color:#6B46C1;">Annuler</a>
    </form>
</section>
@endsection
