<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $university_registry
 * @property string $encrypted_password
 * @property int $role_id
 */
class Roles extends Model
{
    protected static string $table = 'roles';
    protected static array $columns = ['name'];

    public function validates(): void
    {
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
