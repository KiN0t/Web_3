@extends('layouts.app')
@section('title', 'Nouveau projet')
@section('content')
<section>
    <h2>Nouveau projet</h2>
    <form action="{{ route('projets.store') }}" method="POST">
        @csrf
        <input type="text" name="nom" placeholder="Nom du projet" value="{{ old('nom') }}" required>
        <input type="text" name="client" placeholder="Client" value="{{ old('client') }}" required>
        <textarea name="description" placeholder="Description" rows="3">{{ old('description') }}</textarea>
        <select name="statut">
            <option value="actif">Actif</option>
            <option value="en-pause">En pause</option>
            <option value="termine">Terminé</option>
        </select>
        <input type="number" name="budget_heures" placeholder="Budget heures" step="0.5" min="0" value="{{ old('budget_heures') }}" required>
        <button type="submit">Créer le projet</button>
        <a href="{{ route('projets.index') }}" style="text-align:center;color:#6B46C1;">Annuler</a>
    </form>
</section>
@endsection
