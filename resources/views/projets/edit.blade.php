@extends('layouts.app')
@section('title', 'Modifier ' . $projet->nom)
@section('content')
<section>
    <h2>Modifier {{ $projet->nom }}</h2>
    <form action="{{ route('projets.update', $projet) }}" method="POST">
        @csrf @method('PUT')
        <input type="text" name="nom" value="{{ old('nom', $projet->nom) }}" required>
        <input type="text" name="client" value="{{ old('client', $projet->client) }}" required>
        <textarea name="description" rows="3">{{ old('description', $projet->description) }}</textarea>
        <select name="statut">
            @foreach(['actif', 'en-pause', 'termine'] as $s)
            <option value="{{ $s }}" {{ $projet->statut === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <input type="number" name="budget_heures" step="0.5" min="0" value="{{ old('budget_heures', $projet->budget_heures) }}" required>
        <button type="submit">Enregistrer</button>
        <a href="{{ route('projets.index') }}" style="text-align:center;color:#6B46C1;">Annuler</a>
    </form>
</section>
@endsection
