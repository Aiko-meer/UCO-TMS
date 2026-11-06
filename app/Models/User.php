<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
         'employee_id',
         'fullname',
         'email',
         'password',
         'verified',
         'image',
         'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assign()
    {
        return $this->belongsTo(Publishing_request_informaitons::class, 'employee_id', 'produce');
    }

    // In app/Models/User.php

// In app/Models/User.php

public function photoRequests()
{
    return $this->hasMany(Photo_vid_request_informations::class, 'produce', 'employee_id');
}
public function layoutRequests()
{
    return $this->hasMany(Publishing_request_informaitons::class, 'produce', 'employee_id');
}

    /**
     * Per-account notification read receipts. Read state lives here, one row
     * per (user, notification_key), so one account opening a request never
     * clears the badge for anybody else.
     */
    public function notificationReads()
    {
        return $this->hasMany(NotificationRead::class);
    }

    /**
     * Notification keys this account has already opened.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    public function readNotificationKeys()
    {
        return $this->notificationReads()->pluck('notification_key');
    }

    public function hasReadNotification(string $key): bool
    {
        return $this->notificationReads()->where('notification_key', $key)->exists();
    }
}
