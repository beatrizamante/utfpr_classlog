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
class Schedules extends Model
{
    protected static string $table = 'schedules';
    protected static array $columns = ['date'];

    public function validates(): void
    {
        Validations::notEmpty('date', $this);
    }
}
