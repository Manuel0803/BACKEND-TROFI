<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    protected $table = 'trabajo';

    public $timestamps = false; 

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_job');
    }
}