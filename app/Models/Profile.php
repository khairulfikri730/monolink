<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    /**
     * Nama yang tidak boleh dipakai sebagai username publik karena
     * bertabrakan dengan rute aplikasi.
     */
    public const RESERVED_USERNAMES = [
        'admin', 'dashboard', 'login', 'logout', 'register', 'account',
        'l', 'storage', 'up', 'api', 'password', 'settings', 'profile',
        'confirm-password', 'forgot-password', 'reset-password', 'verify-email',
    ];

    /**
     * Username unik berbasis nama lengkap: slug apa adanya, akhiran _2, _3,
     * dst. hanya bila sudah dipakai; cadangan acak setelah 50 tabrakan.
     */
    public static function generateUniqueUsername(string $name): string
    {
        $base = \Illuminate\Support\Str::slug($name, '_');
        if ($base === '' || in_array(\Illuminate\Support\Str::lower($base), self::RESERVED_USERNAMES, true)) {
            $base = 'user';
        }
        $base = \Illuminate\Support\Str::lower($base);

        $candidate = $base;
        for ($i = 2; static::where('username', $candidate)->exists(); $i++) {
            $candidate = $i <= 50 ? $base . '_' . $i : $base . '_' . \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
        }

        return $candidate;
    }

    protected $fillable = [
        'user_id', 'username', 'display_name', 'bio',
        'profile_image', 'logo', 'location', 'website', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('sort_order');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    public function theme(): HasOne
    {
        return $this->hasOne(Theme::class);
    }

    public function analyticsEvents(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name) . '&background=6366f1&color=fff&size=200';
    }
}
