<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property string $ext_id
 * @property string $status
 * @property string $slug
 * @property string $name
 * @property string $img_url
 * @property int $ord
 * @property Carbon $created
 * @property Carbon $modified
 * @property Carbon $synced
 */
class PrankCategory extends Model
{
    use HasFactory;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    const FILLABLE = ['ext_id', 'status', 'slug', 'name', 'img_url', 'ord', 'synced'];
    const ALLOWED_FILTER = ['slug'];
    protected $table = 'app_categories';
    protected $attributes = [
        'ext_id' => '',
        'status' => 'inactive',
        'slug' => '',
        'name' => '',
        'img_url' => '',
        'ord' => 0,
        'synced' => null
    ];
    protected $dates = ['synced'];
    protected $casts = [
        'ord' => 'integer'
    ];
    protected $hidden = ['ext_id', 'status', 'ord', 'synced'];

    // Relations
    public function prankIdeas(): BelongsToMany
    {
        return $this->belongsToMany(PrankIdea::class, 'app_prank_scripts_app_categories', 'app_category_id', 'app_prank_script_id');
    }

    //  Scopes Functions
    public function scopeAllowed(Builder $query){
        $query->where('status', 'active');
    }

    public function scopeFilter(Builder $query, Request $request){
        if ($request->filled('filters') && is_array($request->get('filters'))){
            foreach ($request->get('filters') as $key => $value)
                if (in_array($key, static::ALLOWED_FILTER)) $query->whereIn($key, $value);
        }
    }
}
