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
class Subject extends Model
{
    protected static string $table = 'subjects';
    protected static array $columns = ['name', 'semester'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('semester', $this);
        Validations::uniqueness('name', $this);
    }
}
