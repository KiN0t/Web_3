<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = ['nom', 'description', 'statut', 'client', 'budget_heures', 'user_id'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Heures totales passées sur tous les tickets du projet
    public function heuresTotales()
    {
        return $this->tickets->sum(fn($t) => $t->tempsPasses->sum('heures'));
    }

    // Heures restantes = budget - heures passées
    public function heuresRestantes()
    {
        return $this->budget_heures - $this->heuresTotales();
    }

    public function collaborateurs()
    {
        return $this->belongsToMany(User::class, 'projet_collaborateurs');
    }
}