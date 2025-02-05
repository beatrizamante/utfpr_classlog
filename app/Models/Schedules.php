<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\Database;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use PDO;

use function array_values;
use function date;
use function strtotime;

/**
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property string $day_of_week
 * @property boolean $default_day
 * @property string $date
 * @property string $exceptional_day
 * @property boolean $is_canceled
 * @property int $user_subject_id
 * @property int $classroom_id
 * @property UserSubjects $userSubject
 * @property ClassRoom $classroom
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
  /**
   *
   * @return array<Schedules> Um array de objetos Schedules.
   */
    public static function defaultSchedules(): array
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

  /**
   *
   * @return array<Schedules> Um array de objetos Schedules.
   */
    public static function canceledSchedules(string $date): array
    {

        $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($date)));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
        $sql = "SELECT * FROM schedules WHERE (date BETWEEN :startOfWeek AND :endOfWeek)
                          AND (is_canceled = 1 OR exceptional_day = 1)";
        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':startOfWeek', $startOfWeek);
        $stmt->bindParam(':endOfWeek', $endOfWeek);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($rows as $row) {
            $models[] = new static($row);
        }
        return $models;
    }
  /**
   *
   * @return array<Schedules> Um array de objetos Schedules.
   */

    public static function withCancelAndSubstitutionsCurrentWeek(string $date): array
    {
        $canceleds = (self::canceledSchedules($date));
        $defaults = self::defaultSchedules();

        $indexedSchedules = [];

        foreach ($canceleds as $canceled) {
            $key = "{$canceled->day_of_week}-{$canceled->classroom_id}-{$canceled->start_time}-{$canceled->end_time}";

            if ($canceled->exceptional_day == 1) {
                $indexedSchedules[$key] = $canceled;
                continue;
            }

            if ($canceled->is_canceled == 1 && !isset($indexedSchedules[$key])) {
                $indexedSchedules[$key] = $canceled;
            }
        }

        foreach ($defaults as $default) {
            $key = "{$default->day_of_week}-{$default->classroom_id}-{$default->start_time}-{$default->end_time}";

            if (!isset($indexedSchedules[$key])) {
                $indexedSchedules[$key] = $default;
            }
        }
        return array_values($indexedSchedules);
    }
}
