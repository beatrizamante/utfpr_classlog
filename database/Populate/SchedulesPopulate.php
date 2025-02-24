<?php

namespace Database\Populate;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\Schedules;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjects;
use function var_dump;

class SchedulesPopulate
{
  public static function populate()
  {
    // Criar usuários (professores)
    $usersData = [
      [
        "name" => "Eleandro",
        "university_registry" => "12435837229",
      ],
      [
        "name" => "Emerson",
        "university_registry" => "124358372291",
      ],
      [
        "name" => "Andres",
        "university_registry" => "124358372292",
      ],
      [
        "name" => "Diego",
        "university_registry" => "124358372293",
      ],
      [
        "name" => "Ana",
        "university_registry" => "124358372294",
      ],
      [
        "name" => "Kelly",
        "university_registry" => "124358372295",
      ],
    ];

    $users = [];
    foreach ($usersData as $data) {
      $user = new User([
        "name" => $data["name"],
        "university_registry" => $data["university_registry"],
        "password" => "123",
        "password_confirmation" => "123",
        "role_id" => RolesEnum::PROFESSOR->value
      ]);
      $user->save();
      $users[] = $user;
    }

    // Criar blocos e salas
    $blocksData = [
      "B" => ["B9", "B12", "B7", "B6"],
      "C" => ["C1"],
      "H" => ["H303"],
    ];

    $blocks = [];
    $classrooms = [];
    foreach ($blocksData as $blockName => $classroomNames) {

      $block = new Block(["name" => $blockName]);
      $block->save();
      $blocks[$blockName] = $block;

      foreach ($classroomNames as $classroomName) {
        $classroom = new ClassRoom(["name" => $classroomName, "block_id" => $block->id]);
        $classroom->save();
        $classrooms[] = $classroom;
      }
    }
    // Criar disciplinas
    $subjectsData = [
      "Resolução De Problemas",
      "Projetos De Extensão",
      "Programação Para Dispositivos Móveis",
      "Desenvolvimento De Aplicações Backend",
      "Língua Inglesa No Contexto De Tecnologia Da Informação",
      "Banco De Dados NoSQL",
    ];

    $subjects = [];
    foreach ($subjectsData as $subjectName) {
      $subject = new Subject(["semester" => "4", "name" => $subjectName]);
      $subject->save();
      $subjects[] = $subject;
    }

    // Associar professores às disciplinas
    $userSubjectsData = [
      ["user" => $users[0], "subject" => $subjects[0]],
      ["user" => $users[1], "subject" => $subjects[1]],
      ["user" => $users[2], "subject" => $subjects[2]],
      ["user" => $users[3], "subject" => $subjects[3]],
      ["user" => $users[4], "subject" => $subjects[4]],
      ["user" => $users[5], "subject" => $subjects[5]],
    ];

    $userSubjects = [];
    foreach ($userSubjectsData as $data) {
      $userSubject = new UserSubjects([
        "user_id" => $data["user"]->id,
        "subject_id" => $data["subject"]->id
      ]);
      $userSubject->save();
      $userSubjects[] = $userSubject;
    }

    // Criar horários (schedules)
    $schedulesData = [
      [
        "classroom" => $classrooms[4],
        "user_subject" => $userSubjects[0],
        "start_time" => "19:40",
        "end_time" => "21:20",
        "day_of_week" => 1,
      ],
      [
        "classroom" => $classrooms[0],
        "user_subject" => $userSubjects[1],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 1,

      ],
      [
        "classroom" => $classrooms[1],
        "user_subject" => $userSubjects[2],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 2,
      ],
      [
        "classroom" => $classrooms[2],
        "user_subject" => $userSubjects[3],
        "start_time" => "19:40",
        "end_time" => "21:20",
        "day_of_week" => 2,
      ],
      [
        "classroom" => $classrooms[3],
        "user_subject" => $userSubjects[0],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 3,
      ],
      [
        "classroom" => $classrooms[1],
        "user_subject" => $userSubjects[2],
        "start_time" => "19:40",
        "end_time" => "22:20",
        "day_of_week" => 3,
      ],
      [
        "classroom" => $classrooms[5],
        "user_subject" => $userSubjects[4],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 4,
      ],
      [
        "classroom" => $classrooms[2],
        "user_subject" => $userSubjects[5],
        "start_time" => "19:40",
        "end_time" => "22:20",
        "day_of_week" => 4,
      ],
    ];

    foreach ($schedulesData as $data) {
      $classroom = ClassRoom::findById($data["classroom"]->id);
      $schedule = new Schedules([
        "classroom_id" => $data["classroom"]->id,
        "user_subject_id" => $data["user_subject"]->id,
        "start_time" => $data["start_time"],
        "end_time" => $data["end_time"],
        "default_day" => 1,
        "day_of_week" => $data["day_of_week"],
        "is_canceled" => 0,
        "block_id" => $classroom->block->id,
      ]);
      $schedule->save();
    }
  }
}

