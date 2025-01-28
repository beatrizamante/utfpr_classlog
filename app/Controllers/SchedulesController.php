<?php

namespace App\Controllers;

use App\Models\Schedules;
use App\Models\Subject;
use App\Models\UserSubjects;
use Core\Database\Database;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

use DateTime;
use Exception;
use PDO;
use function array_map;
use function date_create;
use function date_create_from_format;
use function json_encode;

class SchedulesController extends Controller
{
    public function index(): void
    {
      $allSchedules = Schedules::all();
      $schedulesArray = array_map(function ($schedule) {
        return [
          'id' => $schedule->id,
          'start_time' => $schedule->start_time,
          'end_time' => $schedule->end_time,
          'day_of_week' => $schedule->day_of_week,
          'default_day' => $schedule->default_day,
          'exceptional_day' => $schedule->exceptional_day,
          'user_subject_id' => $schedule->user_subject_id,
          'classroom_id' => $schedule->classroom_id,

        ];
      }, $allSchedules);

      echo json_encode($schedulesArray);
    }
    public function create(Request $request): void
    {
      $params = $request->getBody();
      $startTime = $params['start_time'];
      $endTime = $params['end_time'];
      $startTime = DateTime::createFromFormat('H:i', $startTime);
      $endTime = DateTime::createFromFormat('H:i', $endTime);
      try{
        $schedule = new Schedules([
          'start_time' => $startTime->format('H:i'),
          'end_time' => $endTime->format('H:i'),
          'default_day' => $params['default_day'],
          'classroom_id' => $params['classroom_id'],
          'user_subject_id' => $params['user_subject_id'],
          'day_of_week' => $params['day_of_week'],

        ]);
        if(!$this->validatesDateConflict($schedule)){
          $schedule->save();
        } else{
          echo json_encode(['error' => 'Já existe um registro nesse horário e dia']);

        }

      } catch (Exception $exception) {
        echo json_encode(['error' => $exception->getMessage()]);
      }

      echo json_encode(['data' => 'schedules create']);

    }

    public function delete(Request $request): void
    {
      $params = $request->getParams();
      $subject = Schedules::findById($params['id']);
      $subject->destroy();
    }

    public function validatesDateConflict($schedule)
    {
      $sql = "SELECT * FROM schedules WHERE day_of_week = :day_of_week AND start_time <= :end_time AND end_time >= :start_time AND classroom_id = :classroom_id";
      $pdo = Database::getDatabaseConn();
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':day_of_week', $schedule->day_of_week);
      $stmt->bindValue(':start_time', $schedule->start_time);
      $stmt->bindValue(':end_time', $schedule->end_time);
      $stmt->bindValue(':classroom_id', $schedule->classroom_id);
      $stmt->execute();

      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return  count($rows) > 0;
    }
}
