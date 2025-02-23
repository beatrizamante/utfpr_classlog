<?php

namespace App\Controllers;

use App\Models\Schedules;
use App\Models\SchedulesException;
use App\Models\User;
use App\Models\UserSubjects;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use DateTime;
use Lib\FlashMessage;

use function array_map;
use function date;
use function json_encode;
use function locale_get_primary_language;
use function strtotime;

class HomeController extends Controller
{
    public function index(Request $request): void
    {

        $params = $request->getParams();
        $date = date('Y-m-d');
        if (isset($params['date'])) {
            $date = $params['date'];
        }
        $schedules = Schedules::withCancelAndSubstitutionsCurrentWeek($date);

        $schedulesArray = array_map(function ($schedule) {
            return [
            'id' => $schedule->id,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'day_of_week' => $schedule->day_of_week,
            'default_day' => $schedule->default_day,
            'exceptional_day' => $schedule->exceptional_day,
            'subject_professor_id' => $schedule->userSubject->user->id,
            'subject_professor_name' => $schedule->userSubject->user->name,
            'subject_subject_id' => $schedule->userSubject->subject->id,
            'subject_subject_name' => $schedule->userSubject->subject->name,
            'classroom_id' => $schedule->classroom->id,
            'classroom_name' => $schedule->classroom->name,
            'block_id' => $schedule->classroom->block->id,
              'block_name' => $schedule->classroom->block->name,
              'block_photo' => $schedule->classroom->block->photo()->path(),
            'date' => $schedule->date,
            'is_canceled' => $schedule->is_canceled,
            ];
        }, $schedules);

        echo json_encode(['schedules' => $schedulesArray]);
    }
}
