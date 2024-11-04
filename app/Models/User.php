<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function generateUserToken(User $user, $set_role = null): array
    {

        $token = $user->createToken('user')->plainTextToken;

        $user_info = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'tanggal_lahir' => $user->tanggal_lahir,
            'no_hp' => $user->no_hp,
            'foto_profile' => $user->foto_profile,
            'encrypt_id' => Crypt::encrypt($user->id),
            'access_token' => $token,
        ];

        $response_data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') * 60,
            'user_info' => $user_info,
        ];

        return $response_data;
    }

    public function getFotoProfileAttribute()
    {
        if($this->attributes['foto_profile'] != null){
            return $this->attributes['foto_profile'] = env('URL_STORAGE'). '/files/user/' . $this->attributes['foto_profile'];
        }
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

}
