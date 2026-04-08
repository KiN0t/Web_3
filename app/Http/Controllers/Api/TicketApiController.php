<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Projet;
use Illuminate\Http\Request;

class TicketApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $tickets = Ticket::with('projet')->get();
        } elseif ($user->role === 'collaborateur') {
            $projetsIds = $user->projetsCollaborateur()->pluck('projets.id');
            $tickets = Ticket::whereIn('projet_id', $projetsIds)->with('projet')->get();
        } else {
            $projetsIds = Projet::where('user_id', $user->id)->pluck('id');
            $tickets = Ticket::whereIn('projet_id', $projetsIds)->with('projet')->get();
        }

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'statut'          => 'required|in:ouvert,en-cours,ferme',
            'priorite'        => 'required|in:low,medium,high',
            'heures_estimees' => 'required|numeric|min:0',
            'facturable'      => 'boolean',
            'projet_id'       => 'required|exists:projets,id',
        ]);

        $ticket = Ticket::create([
            ...$request->all(),
            'user_id'    => $request->user()->id,
            'facturable' => $request->boolean('facturable'),
        ]);

        return response()->json($ticket->load('projet'), 201);
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load('projet', 'tempsPasses'));
    }
}