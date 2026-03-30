<?php

namespace App\Http\Controllers;

use App\Models\TempsPasse;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TempsPasseController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'heures'      => 'required|numeric|min:0.25',
            'commentaire' => 'nullable|string',
            'date'        => 'required|date',
        ]);

        TempsPasse::create([...$request->all(), 'ticket_id' => $ticket->id, 'user_id' => auth()->id()]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Temps ajouté.');
    }

    public function destroy(TempsPasse $tempsPasse)
    {
        $ticket = $tempsPasse->ticket;
        $tempsPasse->delete();
        return redirect()->route('tickets.show', $ticket)->with('success', 'Temps supprimé.');
    }
}