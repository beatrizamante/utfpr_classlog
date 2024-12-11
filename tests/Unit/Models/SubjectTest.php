<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\Subject;
use App\Models\User;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    private Subject $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Subject([
        'name' => 'H',
          'semester' => '2'
        ]);
        $this->subject->save();
    }

    public function test_should_create_new_subject(): void
    {
        $this->assertCount(1, Subject::all());
    }

    public function test_should_update_subject(): void
    {
        $this->subject->name = 'B';
        $this->subject->save();
        $this->assertEquals('B', $this->subject->name);
    }

    public function test_should_delete_subject(): void
    {
        $this->subject->destroy();

        $this->assertCount(0, Subject::all());
    }
}
