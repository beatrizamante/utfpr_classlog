<?php

namespace App\Controllers;

use App\Models\Schedules;
use App\Models\SchedulesException;
use App\Models\Subject;
use App\Models\UserSubjects;
use Core\Database\Database;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

use DateTime;
use Exception;
use Lib\Authentication\Auth;
use PDO;
use function array_map;
use function date_create;
use function date_create_from_format;
use function json_encode;

class SchedulesExceptionController extends Controller
{
    public function index(): void
    {
      $allScheduleExceptions = SchedulesException::all();
      $scheduleExceptionsArray = array_map(function ($scheduleException) {
        return [
          'id' => $scheduleException->id,
          'date' => $scheduleException->date,
          'is_canceled' => $scheduleException->is_canceled,
          'schedule_id' => $scheduleException->schedule_id,
          'custom_classroom_id' => $scheduleException->custom_classroom_id,
          'custom_user_subject_id' => $scheduleException->custom_user_subject_id,

        ];
      }, $allScheduleExceptions);

      echo json_encode($scheduleExceptionsArray);
    }
    public function create(Request $request): void
    {
      $params = $request->getBody();
//      dd(Auth::user());

      try{
        $schedule = new SchedulesException([
          'date' => $params['date'],
          'is_canceled' => $params['is_canceled'],
          'schedule_id' => $params['schedule_id'],
          'custom_user_subject_id' => $params['custom_user_subject_id'],
          'custom_classroom_id' => $params['custom_classroom_id'],

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
      $sql = "SELECT * FROM schedule_exceptions WHERE date = :date AND schedule_id = :schedule_id";
      $pdo = Database::getDatabaseConn();
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':date', $schedule->date);
      $stmt->bindValue(':schedule_id', $schedule->schedule_id);
      $stmt->execute();

      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return  count($rows) > 0;
    }
}
