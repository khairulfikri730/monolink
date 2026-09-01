<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theme extends Model
{
    protected $fillable = [
        'profile_id', 'template_name', 'background_type', 'background_value',
        'primary_color', 'secondary_color', 'text_color', 'button_color',
        'button_text_color', 'button_style', 'font_family', 'font_size',
        'font_weight', 'layout',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public static function defaults(): array
    {
        return [
            'template_name'      => 'classic',
            'background_type'    => 'SOLID',
            'background_value'   => '#f8fafc',
            'primary_color'      => '#6366f1',
            'secondary_color'    => '#a5b4fc',
            'text_color'         => '#1e293b',
            'button_color'       => '#6366f1',
            'button_text_color'  => '#ffffff',
            'button_style'       => 'ROUNDED',
            'font_family'        => 'Inter',
            'font_size'          => 'md',
            'font_weight'        => 'normal',
            'layout'             => 'center',
        ];
    }
}
