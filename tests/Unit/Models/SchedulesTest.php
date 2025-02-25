<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\Schedules;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjects;
use Core\Database\ActiveRecord\BelongsTo;
use Exception;
use Tests\TestCase;

use function date;
use function strtotime;
use function var_dump;

class SchedulesTest extends TestCase
{
    private string $date;

    private Schedules $cancelSchedule;
    public function setUp(): void
    {
        parent::setUp();

        $this->date = date('Y-m-d');
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
        "block_id" => $classrooms[4]->block->id,
        ],
        [
        "classroom" => $classrooms[0],
        "user_subject" => $userSubjects[1],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 1,
        "block_id" => $classrooms[0]->block->id

        ],
        [
        "classroom" => $classrooms[1],
        "user_subject" => $userSubjects[2],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 2,
        "block_id" => $classrooms[1]->block->id
        ],
        [
        "classroom" => $classrooms[2],
        "user_subject" => $userSubjects[3],
        "start_time" => "19:40",
        "end_time" => "21:20",
        "day_of_week" => 2,
        "block_id" => $classrooms[2]->block->id

        ],
        [
        "classroom" => $classrooms[3],
        "user_subject" => $userSubjects[0],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 3,
        "block_id" => $classrooms[3]->block->id
        ],
        [
        "classroom" => $classrooms[1],
        "user_subject" => $userSubjects[2],
        "start_time" => "19:40",
        "end_time" => "22:20",
        "day_of_week" => 3,
        "block_id" => $classrooms[1]->block->id
        ],
        [
        "classroom" => $classrooms[5],
        "user_subject" => $userSubjects[4],
        "start_time" => "18:40",
        "end_time" => "20:20",
        "day_of_week" => 4,
        "block_id" => $classrooms[5]->block->id
        ],
        [
        "classroom" => $classrooms[2],
        "user_subject" => $userSubjects[5],
        "start_time" => "19:40",
        "end_time" => "22:20",
        "day_of_week" => 4,
        "block_id" => $classrooms[2]->block->id
        ],
        ];
        foreach ($schedulesData as $data) {
            $schedule = new Schedules([
            "classroom_id" => $data["classroom"]->id,
            "user_subject_id" => $data["user_subject"]->id,
            "start_time" => $data["start_time"],
            "end_time" => $data["end_time"],
            "default_day" => 1,
            "day_of_week" => $data["day_of_week"],
            "is_canceled" => 0,
            "block_id" => $data["block_id"],
            ]);
            $schedule->save();
        }

        $schedule = Schedules::findById(1);
        $this->cancelSchedule = new Schedules([
        'start_time' => $schedule->start_time,
        'end_time' => $schedule->end_time,
        'default_day' => 0,
        'classroom_id' => $schedule->classroom_id,
        'user_subject_id' => $schedule->user_subject_id,
        'day_of_week' => $schedule->day_of_week,
        'is_canceled' => 1,
        'date' => $this->date,
        'exceptional_day' => 0,
        'block_id' => $schedule->block_id,
        ]);

        $this->cancelSchedule->save();
    }

    public function test_should_create_new_schedules(): void
    {
        $this->assertCount(9, Schedules::all());
    }

    public function test_should_return_default_dates(): void
    {
        $this->assertCount(8, Schedules::defaultSchedules());
    }


    public function test_should_cancel_schedule(): void
    {

        $this->assertCount(1, Schedules::canceledSchedules($this->date));
    }


    public function teste_should_change_room(): void
    {
        $schedule = Schedules::findById(2);
        $dayOfWeek = (int) date('N', strtotime($this->date));
        $changeSchedule = new Schedules([
        'start_time' => $schedule->start_time,
        'end_time' => $schedule->end_time,
        'default_day' => 0,
        'classroom_id' => $this->cancelSchedule->classroom_id,
        'user_subject_id' => $schedule->user_subject_id,
        'day_of_week' => $dayOfWeek,
        'is_canceled' => 0,
        'date' => $this->date,
        'exceptional_day' => 1,
        'block_id' => $schedule->block_id
        ]);
        $changeSchedule->save();
        $schedules = Schedules::withCancelAndSubstitutionsCurrentWeek($this->date);
        $found = false;

        foreach ($schedules as $schedule) {
            if (
                $schedule->id === $changeSchedule->id &&
                $schedule->classroom_id === $this->cancelSchedule->classroom_id
            ) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'O agendamento com a sala alterada não foi encontrado.');
    }
}
