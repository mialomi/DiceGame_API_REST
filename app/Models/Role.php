<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    use HasFactory;


    protected $fillable = ['name'];

    // establecemos que un rol puede tener de muchos usuarios
    
    public function users(){

        return $this->hasMany(User::class);
    }





}
