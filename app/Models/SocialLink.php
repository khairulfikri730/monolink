<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    protected $fillable = ['profile_id', 'platform', 'url', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function getIconAttribute(): string
    {
        return match($this->platform) {
            'INSTAGRAM'  => '📸',
            'TIKTOK'     => '🎵',
            'YOUTUBE'    => '▶️',
            'FACEBOOK'   => '👤',
            'LINKEDIN'   => '💼',
            'X_TWITTER'  => '🐦',
            default      => '🔗',
        };
    }

    // Monokrom thin lucide — 21st.dev hero-banner style (stroke 1.75)
    // NOTE: lucide@1.43 bundle di unpkg TIDAK mengandung brand icons (instagram/facebook/youtube/linkedin)
    // jadi mapping ke icon lucide yang ada: camera/users/play/briefcase
    public function getLucideIconAttribute(): string
    {
        return match(strtoupper($this->platform)) {
            'INSTAGRAM' => 'camera',
            'TIKTOK'    => 'music-2',
            'YOUTUBE'   => 'play',
            'FACEBOOK'  => 'users',
            'LINKEDIN'  => 'briefcase',
            'X_TWITTER', 'TWITTER', 'X' => 'bird',
            default     => 'link-2',
        };
    }
}
