<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Game extends Model
{
    use HasFactory;
/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['dice1', 'dice2', 'result', 'user_id'];


    public function user() {

        return $this->belongsTo(User::class);
    }

    public function calculate_global_rate(): int|string {

        $total_wins = Game::where('result', '7')->count();
        $total_plays = Game::count();

        if($total_plays == 0) {
            return 0;
        }

        $average_success = ($total_wins / $total_plays) * 100;

        return 'The average of all players is ' . number_format($average_success, 2). ' %';


    }
}