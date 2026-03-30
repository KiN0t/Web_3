<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function signup()
    {
        return view('signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Inscription réussie, veuillez vous connecter.');
    }

    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function logout()
    {
        return redirect()->route('home');
    }

    // variables crées graces a des requetes eloquent qu'est juste du sql
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $totalProjets = \App\Models\Projet::count();
            $totalTickets = \App\Models\Ticket::count();
            $ticketsOuverts = \App\Models\Ticket::where('statut', 'ouvert')->count();
            $ticketsEnCours = \App\Models\Ticket::where('statut', 'en-cours')->count();
            $ticketsFermes = \App\Models\Ticket::where('statut', 'ferme')->count();
            $heuresTotales = \App\Models\TempsPasse::sum('heures');
            $heuresFacturables = \App\Models\TempsPasse::whereHas('ticket', fn($q) => $q->where('facturable', true))->sum('heures');
        } elseif ($user->role === 'collaborateur') {
            $projetsIds = $user->projetsCollaborateur()->pluck('projets.id');
            $totalProjets = $projetsIds->count();
            $totalTickets = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->count();
            $ticketsOuverts = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'ouvert')->count();
            $ticketsEnCours = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'en-cours')->count();
            $ticketsFermes = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'ferme')->count();
            $heuresTotales = \App\Models\TempsPasse::whereHas('ticket', fn($q) => $q->whereIn('projet_id', $projetsIds))->sum('heures');
            $heuresFacturables = \App\Models\TempsPasse::whereHas('ticket', fn($q) => $q->whereIn('projet_id', $projetsIds)->where('facturable', true))->sum('heures');
        } else {
            $projetsIds = \App\Models\Projet::where('user_id', $user->id)->pluck('id');
            $totalProjets = $projetsIds->count();
            $totalTickets = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->count();
            $ticketsOuverts = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'ouvert')->count();
            $ticketsEnCours = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'en-cours')->count();
            $ticketsFermes = \App\Models\Ticket::whereIn('projet_id', $projetsIds)->where('statut', 'ferme')->count();
            $heuresTotales = \App\Models\TempsPasse::whereHas('ticket', fn($q) => $q->whereIn('projet_id', $projetsIds))->sum('heures');
            $heuresFacturables = \App\Models\TempsPasse::whereHas('ticket', fn($q) => $q->whereIn('projet_id', $projetsIds)->where('facturable', true))->sum('heures');
        }

        return view('dashboard', compact( //Compact est une fonction php qui crée un tableau associatif à partir de variables
            'totalProjets', 'totalTickets', 'ticketsOuverts',
            'ticketsEnCours', 'ticketsFermes', 'heuresTotales', 'heuresFacturables'
        ));
    }

    public function profile()
    {
        return view('profile');
    }

    public function settings()
    {
        return view('settings');
    }

    public function promote()
    {
        return view('promote');
    }
}
