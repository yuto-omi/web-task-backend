<?php

namespace App\Modules\NewsCategory\Models;

use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Factories\NewsCategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, News> $news
 * @property-read int|null $news_count
 *
 * @method static \App\Modules\NewsCategory\Factories\NewsCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class NewsCategory extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @var list<string>
     */
    protected $auditExclude = [
        'updated_at',
    ];

    protected static function newFactory(): NewsCategoryFactory
    {
        return NewsCategoryFactory::new();
    }

    /**
     * @return HasMany<News, $this>
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }
}
