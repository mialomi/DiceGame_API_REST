<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;

class Role extends Model
{
    use HasFactory;


    protected $fillable = ['name'];

    // establecemos que un rol puede tener de muchos usuarios
    
    public function users(){

        return $this->hasMany(User::class);
    }





}
