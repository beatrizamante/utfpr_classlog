<?php

namespace Database\Populate;


use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\Schedules;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjects;

class SchedulesPopulate
{
  public static function populate()
  {
    $user = new User([
      "name" => "professor back",
      "university_registry" => "12435837229",
      "password" => "123",
      "password_confirmation" => "123",
      "role_id" => RolesEnum::PROFESSOR->value
    ]);
    $user->save();

    $block = new Block(["name" => "X"]);
    $block->save();
    $classroom = new Classroom(["name" => "103", "block_id" => $block->id]);
    $classroom->save();

    $subject = new Subject([
      "semester" => "4",
      "name" => "back-end 1"
    ]);
    $subject->save();

    $userSubject = new UserSubjects([
      "user_id" => $user->id,
      "subject_id" => $subject->id
    ]);
    $userSubject->save();

    $schedule = new Schedules([
      "classroom_id" => $classroom->id,
      "user_subject_id" => $userSubject->id,
      "start_time" => "19:40",
      "end_time" => "21:20",
      "default_day" => true,
      "day_of_week" => "Terça"
    ]);
    $schedule->save();

  }
}
