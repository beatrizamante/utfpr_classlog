<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $date
 * @property boolean $is_canceled
 * @property int $schedule_id
 * @property int $custom_user_subject_id
 * @property int $custom_classroom_id
 */


class SchedulesException extends Model
{
    protected static string $table = 'schedule_exceptions';
    protected static array $columns = [
      'date',
      'is_canceled',
      'schedule_id',
      'custom_user_subject_id',
      'custom_classroom_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('date', $this);
        Validations::notEmpty('is_canceled', $this);
        Validations::notEmpty('schedule_id', $this);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }
    public function customUserSubject()
    {
        return $this->hasMany(UserSubjects::class, 'custom_user_subject_id');
    }

    public function classroom()
    {
        return $this->hasMany(Classroom::class, 'custom_classroom_id');
    }
}
