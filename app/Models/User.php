<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'status',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * လက်ရှိ Login ဝင်ထားသူ (Auth User) ဟာ ဒီ Target User ကို
     * Edit/Delete/Disable လုပ်ခွင့်ရှိမရှိ စစ်ဆေးတဲ့ Method
     */
    public function canBeManagedBy(User $actor): bool
    {
        // Super Admin ကတော့ ဘယ်သူ့ကိုမဆို Manage လုပ်နိုင်တယ် (ကိုယ့်ကိုယ်ကိုလွဲပြီး)
        if ($actor->isSuperAdmin()) {
            return $actor->id !== $this->id; // ကိုယ့် Account ကိုယ် Disable/Delete မလုပ်နိုင်အောင်
        }

        // ပုံမှန် Admin က Admin/SuperAdmin ကို လက်ဝင်စွမ်းမရှိ
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return false;
        }

        // ပုံမှန် Admin က User (Role='user') ကိုတော့ Manage လုပ်လို့ရတယ်
        return true;
    }
}
