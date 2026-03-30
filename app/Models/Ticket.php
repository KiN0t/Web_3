<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['titre', 'description', 'statut', 'priorite', 'heures_estimees', 'facturable', 'projet_id', 'user_id'];

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tempsPasses()
    {
        return $this->hasMany(TempsPasse::class);
    }

    // Heures passées sur ce ticket
    public function heuresTotales()
    {
        return $this->tempsPasses->sum('heures');
    }

    // Heures restantes sur ce ticket
    public function heuresRestantes()
    {
        return $this->heures_estimees - $this->heuresTotales();
    }

    // Heures à facturer (seulement si facturable)
    public function heuresFacturables()
    {
        return $this->facturable ? $this->heuresTotales() : 0;
    }
}