<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Game extends Model
{
    use HasFactory;

    protected $fillable = ['dice1', 'dice2', 'result', 'user_id'];


    public function user() {

        return $this->belongsTo(Game::class);
    }




}
