<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property string $day_of_week
 * @property boolean $default_day
 * @property int $user_subject_id
 * @property int $classroom_id
 */


class Schedules extends Model
{
    protected static string $table = 'schedules';
    protected static array $columns = [
      'start_time',
      'end_time',
      'day_of_week',
      'default_day',
      'user_subject_id',
      'classroom_id',
      'exceptional_day'
    ];

    public function validates(): void
    {
        Validations::notEmpty('start_time', $this);
        Validations::notEmpty('end_time', $this);
        Validations::notEmpty('user_subject_id', $this);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_subject_id');
    }
    public function classroom()
    {
        return $this->hasMany(ClassRoom::class, 'classroom_id');
    }
}
