<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @property int $id
 * @property string $ext_id
 * @property string $slug
 * @property string $title
 * @property int $likes
 * @property int $our_likes
 * @property int $our_dislikes
 * @property int $our_favorites
 * @property int $views
 * @property int $sent
 * @property int $our_sent
 * @property boolean $prank_of_the_week
 * @property string $tags
 * @property string $description
 * @property string $share_text
 * @property string $img_url
 * @property int $default_app_character_id
 * @property Carbon $created
 * @property Carbon $modified
 * @property Carbon $synced
 */
class PrankIdea extends Model
{
    use HasFactory;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    const FILLABLE = ['ext_id', 'slug', 'title', 'likes', 'our_likes', 'our_dislikes', 'our_favorites', 'views', 'sent', 'our_sent', 'prank_of_the_week', 'tags', 'description', 'share_text', 'img_url', 'default_app_character_id', 'synced'];
    protected $table = 'app_prank_scripts';
    protected $attributes = [
        'ext_id' => '',
        'slug' => '',
        'title' => '',
        'likes' => 0,
        'our_likes' => 0,
        'our_dislikes' => 0,
        'our_favorites' => 0,
        'views' => 0,
        'sent' => 0,
        'our_sent' => 0,
        'prank_of_the_week' => false,
        'tags' => '',
        'description' => '',
        'share_text' => '',
        'img_url' => '',
        'default_app_character_id' => 0,
        'synced' => null
    ];
    protected $dates = ['synced'];
    protected $casts = [
        'prank_of_the_week' => 'boolean',
        'likes' => 'integer',
        'our_likes' => 'integer',
        'our_dislikes' => 'integer',
        'our_favorites' => 'integer',
        'views' => 'integer',
        'sent' => 'integer',
        'our_sent' => 'integer',
        'default_app_character_id' => 'integer',
    ];

    // Relations
    public function prankCategories(): BelongsToMany
    {
        return $this->belongsToMany(PrankCategory::class, 'app_prank_scripts_app_categories', 'app_prank_script_id', 'app_category_id');
    }
    public function prankCategoriesPivot(): HasMany
    {
        return $this->hasMany(PrankIdeaCategoryPivot::class, 'app_prank_script_id', 'id');
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'app_prank_scripts_app_characters', 'app_prank_script_id', 'app_character_id');
    }

    public function defaultCharacter(): HasOneThrough
    {
        return $this->hasOneThrough(
            Character::class,
            PrankIdeaCharacterPivot::class,
            'app_prank_script_id',
            'id',
            'id',
            'app_character_id',
        )->where('app_prank_scripts_app_characters.is_default', 1);
    }

    // Scopes Functions
    public function scopeFilter(Builder $query, Request $request){
        if ($request->filled('filters') && is_array($request->get('filters'))){
            $filters = $request->get('filters');
            if (isset($filters['category']) && is_string($filters['category']))
                $query->whereHas('prankCategories', function (Builder $q) use ($filters){
                    $q->where('slug', $filters['category']);
                });
        }
    }

    public function scopeSearch(Builder $query, Request $request){
        if ($request->filled('s')){
            $query->where('title', 'like', "{$request->get('s')}%");
        }
    }

    // Check Functions : boolean
    public function hasDefaultCharacter(): bool
    {
        return !!$this->default_app_character_id;
    }

    // Helper Functions
    public function tags(): array
    {
        return explode(',', $this->tags);
    }
}
