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
class UserSubjects extends Model
{
    protected static string $table = 'user_subjects';
    protected static array $columns = ['user_id', 'subject_id'];

    public function validates(): void
    {
        Validations::notEmpty('user_id', $this);
        Validations::notEmpty('subject_id', $this);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
