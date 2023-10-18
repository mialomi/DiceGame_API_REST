<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

//use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //asignamos que un usuario tiene un rol y cada rol puede tener varios usuarios asociados

    public function role() {

        return $this->belongsTo(Role::class);

    }

    public function games(){

        return $this->hasMany(Game::class);
    }

    
    // metodo para calcular su porcentaje de éxito
    public function calculate_rates(int $id) : string {

        $total_rolls = Game::where('user_id', $id)->count();

        $success_rolls = Game::where('user_id', $id)
                        ->where('result','7')->count();
        
        if($total_rolls > 0){
            $success_rate = ($success_rolls / $total_rolls) * 100;
                return 'The success rate is '. number_format($success_rate, 2) . ' % ';
        }
        else {
            return 'No records found.';
        }

    }

    //método para listar todos los jugadores que tienen juagadas
    public function get_players() : array {

        $players = User::where('role_id', '2')
                    ->whereHas('games')            
                    ->get();
        
        $players_list = [];

        foreach ($players as $player) {
            
            $player = [
                'nickname' => $player->nickname,
                'rate' => $player->calculate_rates($player->id),
            ];

            $players_list[] = $player;
        };  

        return $players_list;

    }    





}