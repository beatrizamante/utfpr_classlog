<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\Database;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use PDO;

/**
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property string $day_of_week
 * @property boolean $default_day
 * @property string $date
 * @property boolean $is_canceled
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
      'exceptional_day',
      'date',
      'is_canceled',
    ];

    public function validates(): void
    {
        Validations::notEmpty('start_time', $this);
        Validations::notEmpty('end_time', $this);
        Validations::notEmpty('user_subject_id', $this);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }
    public function userSubject(): BelongsTo
    {
      return $this->belongsTo(UserSubjects::class, 'user_subject_id');
    }

    public static function defaultSchedules()
    {
      $sql = "SELECT * FROM schedules WHERE date IS NULL";
      $pdo = Database::getDatabaseConn();
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $models = [];
      foreach ($rows as $row) {
        $models[] = new static($row);
      }
      return $models;
    }
}
