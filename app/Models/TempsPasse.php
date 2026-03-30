<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempsPasse extends Model
{
    protected $fillable = ['heures', 'commentaire', 'date', 'ticket_id', 'user_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}