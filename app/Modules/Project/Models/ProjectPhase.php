<?php

namespace App\Modules\Project\Models;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $project_id
 * @property int|null $assignee_id
 * @property string $name
 * @property string $status
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property numeric|null $estimated_hours
 * @property int $sort_order
 * @property int|null $progress_rate
 * @property string|null $created_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $assignee
 * @property-read Project|null $project
 * @property-read Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 *
 * @method static Builder<static>|ProjectPhase inProgress()
 * @method static Builder<static>|ProjectPhase newModelQuery()
 * @method static Builder<static>|ProjectPhase newQuery()
 * @method static Builder<static>|ProjectPhase onlyTrashed()
 * @method static Builder<static>|ProjectPhase query()
 * @method static Builder<static>|ProjectPhase whereAssigneeId($value)
 * @method static Builder<static>|ProjectPhase whereCreatedAt($value)
 * @method static Builder<static>|ProjectPhase whereDeletedAt($value)
 * @method static Builder<static>|ProjectPhase whereEndDate($value)
 * @method static Builder<static>|ProjectPhase whereEstimatedHours($value)
 * @method static Builder<static>|ProjectPhase whereId($value)
 * @method static Builder<static>|ProjectPhase whereName($value)
 * @method static Builder<static>|ProjectPhase whereProgressRate($value)
 * @method static Builder<static>|ProjectPhase whereProjectId($value)
 * @method static Builder<static>|ProjectPhase whereSortOrder($value)
 * @method static Builder<static>|ProjectPhase whereStartDate($value)
 * @method static Builder<static>|ProjectPhase whereStatus($value)
 * @method static Builder<static>|ProjectPhase withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|ProjectPhase withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ProjectPhase extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'assignee_id',
        'name',
        'status',
        'start_date',
        'end_date',
        'estimated_hours',
        'sort_order',
        'progress_rate',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'estimated_hours' => 'decimal:1',
            'sort_order' => 'integer',
            'progress_rate' => 'integer',
        ];
    }

    // ----------------
    // リレーション
    // ----------------

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'phase_id');
    }

    // ----------------
    // スコープ
    // ----------------

    /** 進行中のフェーズ */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }
}
