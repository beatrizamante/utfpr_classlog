<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjects;
use Tests\TestCase;

class UserSubjectTest extends TestCase
{
    private UserSubjects $userSubject;
    private User $user;
    private User $user2;
    private Subject $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
        'name' => 'User 1',
        'university_registry' => '654987321',
        'password' => '123456',
        'password_confirmation' => '123456',
        'role_id' => RolesEnum::PROFESSOR->value
        ]);

        $this->user->save();

        $this->user2 = new User([
        'name' => 'User 2',
        'university_registry' => '653387321',
        'password' => '123456',
        'password_confirmation' => '123456',
        'role_id' => RolesEnum::PROFESSOR->value
        ]);

        $this->subject = new Subject([
        'name' => 'H',
        'semester' => '2'
        ]);
        $this->subject->save();

        $this->userSubject = new UserSubjects([
        'user_id' => $this->user->id,
          'subject_id' => $this->subject->id
        ]);
        $this->userSubject->save();
    }

    public function test_should_create_new_userSubject(): void
    {
        $this->assertCount(1, UserSubjects::all());
    }

    public function test_should_update_userSubject(): void
    {
        $this->userSubject->user_id = $this->user2->id;
        $this->userSubject->save();
        $this->assertEquals($this->user2->id, $this->userSubject->user_id);
    }

    public function test_should_delete_userSubject(): void
    {
        $this->userSubject->destroy();

        $this->assertCount(0, UserSubjects::all());
    }
}
