<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $ext_id
 * @property string $status
 * @property string $slug
 * @property string $title
 * @property boolean $is_influencer
 * @property string $facebook_url
 * @property string $youtube_url
 * @property string $img_url
 * @property int $ord
 * @property string $data
 * @property Carbon $created
 * @property Carbon $modified
 * @property Carbon $synced
 */
class Character extends Model
{
    use HasFactory;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    const FILLABLE = ['ext_id', 'status', 'slug', 'title', 'is_influencer', 'facebook_url', 'youtube_url', 'img_url', 'ord', 'data', 'synced'];
    protected $table = 'app_characters';
    protected $attributes = [
        'ext_id' => '',
        'status' => 'inactive',
        'slug' => '',
        'title' => '',
        'is_influencer' => false,
        'facebook_url' => '',
        'youtube_url' => '',
        'img_url' => '',
        'ord' => 0,
        'data' => '',
        'synced' => null
    ];
    protected $dates = ['synced'];
    protected $casts = [
        'is_influencer' => 'boolean',
        'ord' => 'integer'
    ];

    // Relations
    public function prankIdea(): HasOne
    {
        return $this->hasOne(PrankIdea::class, 'default_app_character_id', 'id');
    }

    public function prankIdeas(): BelongsToMany
    {
        return $this->belongsToMany(PrankIdea::class, 'app_prank_scripts_app_characters', 'app_character_id', 'app_prank_script_id');
    }

}
