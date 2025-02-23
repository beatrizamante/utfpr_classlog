<?php

namespace Tests\Unit\Models;

use App\Enums\RolesEnum;
use App\Models\Block;
use App\Models\User;
use Tests\TestCase;

class BlockTest extends TestCase
{
    private Block $block;
    private Block $block2;

    public function setUp(): void
    {
        parent::setUp();

        $this->block = new Block([
        'name' => 'H',
        ]);
        $this->block->save();

        $this->block2 = new Block([
        'name' => 'X',
        ]);
        $this->block2->save();
    }

    public function test_should_create_new_block(): void
    {
        $this->assertCount(2, Block::all());
    }


    public function test_should_update_block(): void
    {
        $this->block->name = 'B';
        $this->block->save();
        $this->assertEquals('B', $this->block->name);
    }

    public function test_shouldnt_crete_block_with_duplicate_name(): void
    {
        $block2 = new Block([
        'name' => 'H',
        ]);
        $block2->save();
        $this->assertCount(2, Block::all());
    }

    public function test_should_delete_block(): void
    {
        $this->block->destroy();

        $this->assertCount(1, Block::all());
    }
}
