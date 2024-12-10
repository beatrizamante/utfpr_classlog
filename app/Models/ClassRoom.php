<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

use function array_map;

/**
 * @property int $id
 * @property string $name
 * @property string $university_registry
 * @property string $encrypted_password
 * @property int $role_id
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
}
