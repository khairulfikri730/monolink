<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theme extends Model
{
    protected $fillable = [
        'profile_id', 'template_name', 'background_type', 'background_value',
        'gradient_direction', 'gradient_colors', 'background_image',
        'primary_color', 'secondary_color', 'text_color', 'button_color',
        'button_text_color', 'button_style', 'font_family', 'font_size',
        'font_weight', 'layout',
    ];

    protected $casts = [
        'gradient_colors' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public static function defaults(): array
    {
        return [
            'template_name'      => 'linktree-navy',
            'background_type'    => 'SOLID',
            'background_value'   => '#071A8C',
            'primary_color'      => '#ffffff',
            'secondary_color'    => '#ffffff',
            'text_color'         => '#ffffff',
            'button_color'       => '#ffffff',
            'button_text_color'  => '#ffffff',
            'button_style'       => 'OUTLINE',
            'font_family'        => 'Inter',
            'font_size'          => 'md',
            'font_weight'        => 'normal',
            'layout'             => 'center',
        ];
    }
}
