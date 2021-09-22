<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turismo extends Model
{
    use HasFactory;
    public function galerias()
    {
        return $this->hasMany(Galeria::class,'turismo_id')->select('id','foto');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class,'turismo_id')->latest();
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];

}
