<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $projets = Projet::withCount('tickets')->get();
        } elseif ($user->role === 'collaborateur') {
            $projets = $user->projetsCollaborateur()->withCount('tickets')->get();
        } else {
            // client
            $projets = Projet::where('user_id', $user->id)->withCount('tickets')->get();
        }

        return view('projets.index', compact('projets'));
    }

    public function create()
    {
        return view('projets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'client'        => 'required|string|max:255',
            'description'   => 'nullable|string',
            'statut'        => 'required|in:actif,en-pause,termine',
            'budget_heures' => 'required|numeric|min:0',
        ]);

        Projet::create([...$request->all(), 'user_id' => auth()->id()]);

        return redirect()->route('projets.index')->with('success', 'Projet créé avec succès.');
    }

    public function show(Projet $projet)
    {
        $user = auth()->user();

        if ($user->role === 'collaborateur' && !$projet->collaborateurs->contains($user->id)) {
            abort(403);
        }
        if ($user->role === 'client' && $projet->user_id !== $user->id) {
            abort(403);
        }

        $projet->load('tickets.tempsPasses', 'collaborateurs');
        return view('projets.show', compact('projet'));
    }

    public function edit(Projet $projet)
    {
        return view('projets.edit', compact('projet'));
    }

    public function update(Request $request, Projet $projet)
    {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'client'        => 'required|string|max:255',
            'description'   => 'nullable|string',
            'statut'        => 'required|in:actif,en-pause,termine',
            'budget_heures' => 'required|numeric|min:0',
        ]);

        $projet->update($request->all());

        return redirect()->route('projets.index')->with('success', 'Projet mis à jour.');
    }

    public function destroy(Projet $projet)
    {
        $projet->delete();
        return redirect()->route('projets.index')->with('success', 'Projet supprimé.');
    }
}