<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Projet;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $tickets = Ticket::with('projet')->get();
        } elseif ($user->role === 'collaborateur') {
            $projetsIds = $user->projetsCollaborateur()->pluck('projets.id');
            $tickets = Ticket::whereIn('projet_id', $projetsIds)->with('projet')->get();
        } else {
            $projetsIds = Projet::where('user_id', $user->id)->pluck('id');
            $tickets = Ticket::whereIn('projet_id', $projetsIds)->with('projet')->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $projets = Projet::where('user_id', auth()->id())->get();
        return view('tickets.create', compact('projets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'statut'         => 'required|in:ouvert,en-cours,ferme',
            'priorite'       => 'required|in:low,medium,high',
            'heures_estimees'=> 'required|numeric|min:0',
            'facturable'     => 'boolean',
            'projet_id'      => 'required|exists:projets,id',
        ]);

        Ticket::create([...$request->all(), 'user_id' => auth()->id(), 'facturable' => $request->boolean('facturable')]);

        return redirect()->route('tickets.index')->with('success', 'Ticket créé.');
    }

    public function show(Ticket $ticket)
    {
        $user = auth()->user();

        if ($user->role === 'collaborateur' && !$ticket->projet->collaborateurs->contains($user->id)) {
            abort(403);
        }

        $ticket->load('tempsPasses', 'projet');
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $projets = Projet::where('user_id', auth()->id())->get();
        return view('tickets.edit', compact('ticket', 'projets'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'titre'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'statut'         => 'required|in:ouvert,en-cours,ferme',
            'priorite'       => 'required|in:low,medium,high',
            'heures_estimees'=> 'required|numeric|min:0',
            'facturable'     => 'boolean',
            'projet_id'      => 'required|exists:projets,id',
        ]);

        $ticket->update([...$request->all(), 'facturable' => $request->boolean('facturable')]);

        return redirect()->route('tickets.index')->with('success', 'Ticket mis à jour.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé.');
    }
}