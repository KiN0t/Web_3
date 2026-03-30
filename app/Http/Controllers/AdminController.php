<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Projet;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users  = User::all();
        $projets = Projet::with('collaborateurs')->get();
        return view('admin.index', compact('users', 'projets'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,collaborateur,client']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Rôle mis à jour.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->update($request->only('name', 'email'));
        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function addCollaborateur(Request $request, Projet $projet)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $projet->collaborateurs()->syncWithoutDetaching([$request->user_id]);
        return back()->with('success', 'Collaborateur ajouté.');
    }

    public function removeCollaborateur(Projet $projet, User $user)
    {
        $projet->collaborateurs()->detach($user->id);
        return back()->with('success', 'Collaborateur retiré.');
    }
}