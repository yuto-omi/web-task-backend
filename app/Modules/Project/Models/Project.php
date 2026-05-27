<?php

namespace App\Modules\Project\Models;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $created_by
 * @property string $name
 * @property string|null $client_name
 * @property string $status
 * @property Carbon|null $start_date
 * @property Carbon|null $deadline
 * @property numeric|null $estimated_hours
 * @property string|null $memo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $creator
 * @property-read Collection<int, User> $members
 * @property-read int|null $members_count
 * @property-read Collection<int, ProjectPhase> $phases
 * @property-read int|null $phases_count
 * @property-read Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 *
 * @method static Builder<static>|Project forUser(int $userId)
 * @method static Builder<static>|Project inProgress()
 * @method static Builder<static>|Project newModelQuery()
 * @method static Builder<static>|Project newQuery()
 * @method static Builder<static>|Project onlyTrashed()
 * @method static Builder<static>|Project query()
 * @method static Builder<static>|Project whereClientName($value)
 * @method static Builder<static>|Project whereCreatedAt($value)
 * @method static Builder<static>|Project whereCreatedBy($value)
 * @method static Builder<static>|Project whereDeadline($value)
 * @method static Builder<static>|Project whereDeletedAt($value)
 * @method static Builder<static>|Project whereEstimatedHours($value)
 * @method static Builder<static>|Project whereId($value)
 * @method static Builder<static>|Project whereMemo($value)
 * @method static Builder<static>|Project whereName($value)
 * @method static Builder<static>|Project whereStartDate($value)
 * @method static Builder<static>|Project whereStatus($value)
 * @method static Builder<static>|Project whereUpdatedAt($value)
 * @method static Builder<static>|Project withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Project withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'name',
        'client_name',
        'status',
        'start_date',
        'deadline',
        'estimated_hours',
        'memo',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'deadline' => 'date',
            'estimated_hours' => 'decimal:1',
        ];
    }

    // ----------------
    // リレーション
    // ----------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('created_at');
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('sort_order');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ----------------
    // スコープ
    // ----------------

    /** 進行中のプロジェクト */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    /** 指定ユーザーが参加しているプロジェクト */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('members', fn ($q) => $q->where('user_id', $userId));
    }

    // ----------------
    // ヘルパー
    // ----------------

    /** 進捗率（完了タスク数 ÷ 全タスク数） */
    public function progressRate(): float
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return 0.0;
        }

        $done = $this->tasks()->where('status', 'done')->count();

        return round($done / $total * 100, 1);
    }
}
