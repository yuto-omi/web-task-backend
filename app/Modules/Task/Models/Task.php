<?php

namespace App\Modules\Task\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $project_id
 * @property int|null $phase_id
 * @property int|null $parent_task_id
 * @property int $assignee_id
 * @property int $created_by
 * @property string $title
 * @property string|null $memo
 * @property string $status
 * @property string|null $priority
 * @property string|null $type_tag
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property numeric|null $estimated_hours
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $assignee
 * @property-read User $creator
 * @property-read Task|null $parentTask
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $subTasks
 * @property-read int|null $sub_tasks_count
 * @method static Builder<static>|Task assignedTo(int $userId)
 * @method static Builder<static>|Task dueToday()
 * @method static Builder<static>|Task incomplete()
 * @method static Builder<static>|Task newModelQuery()
 * @method static Builder<static>|Task newQuery()
 * @method static Builder<static>|Task onlyTrashed()
 * @method static Builder<static>|Task personal()
 * @method static Builder<static>|Task query()
 * @method static Builder<static>|Task whereAssigneeId($value)
 * @method static Builder<static>|Task whereCreatedAt($value)
 * @method static Builder<static>|Task whereCreatedBy($value)
 * @method static Builder<static>|Task whereDeletedAt($value)
 * @method static Builder<static>|Task whereDueDate($value)
 * @method static Builder<static>|Task whereEstimatedHours($value)
 * @method static Builder<static>|Task whereId($value)
 * @method static Builder<static>|Task whereMemo($value)
 * @method static Builder<static>|Task whereParentTaskId($value)
 * @method static Builder<static>|Task wherePhaseId($value)
 * @method static Builder<static>|Task wherePriority($value)
 * @method static Builder<static>|Task whereProjectId($value)
 * @method static Builder<static>|Task whereSortOrder($value)
 * @method static Builder<static>|Task whereStatus($value)
 * @method static Builder<static>|Task whereTitle($value)
 * @method static Builder<static>|Task whereTypeTag($value)
 * @method static Builder<static>|Task whereUpdatedAt($value)
 * @method static Builder<static>|Task withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Task withoutTrashed()
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'phase_id',
        'parent_task_id',
        'assignee_id',
        'created_by',
        'title',
        'memo',
        'status',
        'priority',
        'type_tag',
        'due_date',
        'estimated_hours',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'due_date'         => 'date',
            'estimated_hours'  => 'decimal:1',
            'sort_order'       => 'integer',
        ];
    }

    // ----------------
    // リレーション
    // ----------------

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** 親タスク */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /** サブタスク（1階層のみ） */
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    // ----------------
    // スコープ
    // ----------------

    /** 個人タスクのみ（project_id = NULL） */
    public function scopePersonal(Builder $query): Builder
    {
        return $query->whereNull('project_id');
    }

    /** 指定ユーザーのタスク */
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assignee_id', $userId);
    }

    /** 未完了のタスク */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done');
    }

    /** 今日期限のタスク */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_date', today());
    }

    // ----------------
    // ヘルパー
    // ----------------

    /** 個人タスクかどうか */
    public function isPersonal(): bool
    {
        return is_null($this->project_id);
    }

    /** 完了タスクかどうか */
    public function isCompleted(): bool
    {
        return $this->status === 'done';
    }
}
