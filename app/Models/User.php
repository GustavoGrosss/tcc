<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'usuarios';

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'documento',
        'tipo',
        'data_nascimento',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected static function booted()
    {
        static::created(function ($user) {
            $currentUser = Auth::user();

            if ($currentUser && $currentUser->tipo === 'T') {
                TitularesSecundarios::create([
                    'id_titular' => $currentUser->id,
                    'id_secundario' => $user->id,
                ]);
            }
        });
    }

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
        'password'          => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function lembretes(): BelongsToMany
    {
        return $this->belongsToMany(Lembretes::class, 'lembrete_usuario', 'id_destinatario', 'id_lembrete');
    }
}
