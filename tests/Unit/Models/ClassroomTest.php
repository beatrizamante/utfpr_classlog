<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\User;
use http\Message\Body;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    private ClassRoom $classroom;
    private Block $block;

    public function setUp(): void
    {
        parent::setUp();

        $this->block = new Block([
          'name' => "Z"
        ]);

        $this->block->save();

        $this->classroom = new ClassRoom([
        'name' => '1020',
          'block_id' => $this->block->id,
        ]);
        $this->classroom->save();
    }

    public function test_should_create_new_classroom(): void
    {
        $this->assertCount(1, ClassRoom::all());
    }

    public function test_should_return_block_name(): void
    {
        $blockName = $this->classroom->block->name;

        $this->assertEquals($this->block->name, $blockName);
    }


    public function test_should_update_classroom(): void
    {
        $this->classroom->name = '1030';
        $this->classroom->save();
        $this->assertEquals('1030', $this->classroom->name);
    }

    public function test_should_delete_classroom(): void
    {
        $this->classroom->destroy();

        $this->assertCount(0, ClassRoom::all());
    }
}
