<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjects;
use Core\Database\ActiveRecord\BelongsTo;
use Exception;
use Tests\TestCase;

class UserSubjectsTest extends TestCase
{
    private UserSubjects $userSubjects;
    private Subject $subject;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Subject([
        'name' => 'H',
        'semester' => '2'
        ]);
        $this->subject->save();

        $this->user = new User([
        'name' => 'User 1',
        'university_registry' => '654987321',
        'password' => '123456',
        'password_confirmation' => '123456',
        'role_id' => RolesEnum::PROFESSOR->value
        ]);
        $this->user->save();

        $this->userSubjects = new UserSubjects([
        'user_id' => $this->user->id,
        'subject_id' => $this->subject->id
        ]);

        $this->userSubjects->save();
    }

    public function test_should_create_new_user_subject(): void
    {
        $this->assertCount(1, UserSubjects::all());
    }


    public function test_should_delete_subject(): void
    {
        $this->userSubjects->destroy();

        $this->assertCount(0, UserSubjects::all());
    }

    public function test_user_subject_id_shold_be_unique(): void
    {
        $newUserSubject = new UserSubjects([
        'user_id' => $this->user->id,
        'subject_id' => $this->subject->id
        ]);

        $newUserSubject->save();
        $this->assertEquals($newUserSubject->errors['subject_id'], 'já existe um registro com esse dado');
    }

    public function test_user_subject_belongs_to_user(): void
    {
        $this->assertInstanceOf(BelongsTo::class, $this->userSubjects->user());
    }
}
