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
}
