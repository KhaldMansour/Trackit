<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assignee_id'
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED = 'canceled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELED,
        ];
    }

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function dependencies()
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'task_id',
            'dependency_task_id'
        );
    }

    public function dependents()
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'dependency_task_id',
            'task_id'
        );
    }

    // Define the assignee relationship
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
