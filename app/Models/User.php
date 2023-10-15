<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Api\UserController;
use App\Models\Role;
use App\Models\Game;

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

    public function calculate_rates($id) {

        //$user = $request->user()->id;
        $total_rolls = Game::where('user_id', $id)->count();
        $success_rolls = Game::where('user_id', $id)
                        ->where('result','7')->count();
        
        if($total_rolls > 0){
            $success_rate = ($success_rolls / $total_rolls) * 100;
            $success_rate = number_format($success_rate, 2);
            
            return 'Your success rate is '. number_format($success_rate, 2) . '%';
            
            /*response()->json([
                'message' => 'Your success rate is ' . $success_rate.'%'
            ]);*/
        }
        else {
            return 'No records found.';
            
        }
  
}

}