<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use PHPStan\Parallel\Schedule;

use function array_map;

/**
 * @property int $id
 * @property string $name
 * @property int $block_id
 * @property Block $block
 */
class ClassRoom extends Model
{
    protected static string $table = 'classrooms';
    protected static array $columns = ['name', 'block_id'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('block_id', $this);
        Validations::uniqueness('name', $this);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'classroom_id');
    }
}
