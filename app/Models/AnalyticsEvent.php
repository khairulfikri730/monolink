<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    protected $fillable = ['profile_id', 'link_id', 'event_type', 'ip_address', 'user_agent'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
