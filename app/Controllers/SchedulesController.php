<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\Roles;
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
use function date;
use function date_create;
use function date_create_from_format;
use function json_encode;
use function strtotime;

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
              'date' => $schedule->date,
              'is_canceled' => $schedule->is_canceled,
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
        try {
            $schedule = new Schedules([
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'default_day' => $params['default_day'],
            'classroom_id' => $params['classroom_id'],
            'user_subject_id' => $params['user_subject_id'],
              'day_of_week' => $params['day_of_week'],
              'is_canceled' => 0,

            ]);
            if (!$this->validatesDateConflict($schedule)) {
                $schedule->save();
            } else {
                echo json_encode(['error' => 'Já existe um registro nesse horário e dia']);
            }
        } catch (Exception $exception) {
            echo json_encode(['error' => $exception->getMessage()]);
        }

        echo json_encode(['data' => 'schedules create']);
    }

    public function creatreCancelSchedule(Request $request): void
    {
        $params = $request->getBody();
        $id = $params['id'];
        $date = $params['date'];
        $schedule = Schedules::findById($id);

        if ($this->currentUser()->id != $schedule->userSubject->user->id) {
            if ($this->currentUser()->roleName() != 'admin') {
                echo json_encode(['message' => 'Somente o usuário encarregado da aula pode cancela-la']);
            }
        }
        $cancelSchedule = new Schedules([
        'start_time' => $schedule->start_time,
        'end_time' => $schedule->end_time,
        'default_day' => 0,
        'classroom_id' => $schedule->classroom_id,
        'user_subject_id' => $schedule->user_subject_id,
        'day_of_week' => $schedule->day_of_week,
        'is_canceled' => 1,
        'date' => $date,
        'exceptional_day' => 0
        ]);
        if (!$this->validatesCancelDateConflict($cancelSchedule)) {
            $cancelSchedule->save();
            echo json_encode($cancelSchedule);
        } else {
            echo json_encode(['error' => 'Já existe um cancelamento nesse horário e dia']);
        }
    }

    public function deleteCancelSchedule(Request $request): void
    {
        $params = $request->getParams();
        $schedule = Schedules::findById($params['id']);
        if ($this->currentUser()->id != $schedule->userSubject->user->id) {
            if ($this->currentUser()->roleName() != 'admin') {
                echo json_encode(['message' => 'Somente o usuário encarregado da aula pode cancela-la']);
            }
        }
        if ($schedule->is_canceled != 1) {
            echo json_encode(['error' => 'Só é possível deletar um agendamento de cancelamento']);
            return;
        }
        $schedule->destroy();
    }

    public function roomChange(Request $request): void
    {
        $params = $request->getBody();
        $scheduleId = $params['schedule_id'];
        $classroomId = $params['classroom_id'];
        $date = $params['date'];
        $startTime = $params['start_time'];
        $endTime = $params['end_time'];

        $schedule = Schedules::findById($scheduleId);
        $dayOfWeek = (int) date('N', strtotime($date));
        $changeSchedule = new Schedules([
          'start_time' => $startTime,
          'end_time' => $endTime,
          'default_day' => 0,
          'classroom_id' => $classroomId,
          'user_subject_id' => $schedule->user_subject_id,
          'day_of_week' => $dayOfWeek,
          'is_canceled' => 0,
          'date' => $date,
          'exceptional_day' => 1,
        ]);
        if ($this->validatesCancelDateConflict($changeSchedule)) {
            if (!$this->validatesRoomChangeDateConflict($changeSchedule)) {
                $changeSchedule->save();
                echo json_encode($changeSchedule);
            } else {
                echo json_encode(['error' => 'Conflito de mudança de sala para esse horário e dia']);
            }
        } else {
            echo json_encode(['error' => 'Não há cancelamento registrado nesse horário e dia']);
        }
    }


    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $subject = Schedules::findById($params['id']);
        $subject->destroy();
    }

    public function validatesDateConflict(Schedules $schedule): bool
    {
        $sql = "SELECT * FROM schedules WHERE day_of_week = :day_of_week
                          AND start_time <= :end_time
                          AND end_time >= :start_time
                          AND classroom_id = :classroom_id";
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

    public function validatesCancelDateConflict(Schedules $schedule): bool
    {
        $sql = "SELECT * FROM schedules WHERE date = :date
                          AND start_time <= :end_time
                          AND end_time >= :start_time
                          AND classroom_id = :classroom_id
                          AND is_canceled = 1
                          ";
        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':date', $schedule->date);
        $stmt->bindValue(':start_time', $schedule->start_time);
        $stmt->bindValue(':end_time', $schedule->end_time);
        $stmt->bindValue(':classroom_id', $schedule->classroom_id);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return  count($rows) > 0;
    }
    public function validatesRoomChangeDateConflict(Schedules $schedule): bool
    {
        $sql = "SELECT * FROM schedules WHERE date = :date
                          AND start_time <= :end_time
                          AND end_time >= :start_time
                          AND classroom_id = :classroom_id
                          AND exceptional_day = 1";
        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':date', $schedule->date);
        $stmt->bindValue(':start_time', $schedule->start_time);
        $stmt->bindValue(':end_time', $schedule->end_time);
        $stmt->bindValue(':classroom_id', $schedule->classroom_id);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return  count($rows) > 0;
    }
}
