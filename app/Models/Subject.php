<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

use function array_map;

/**
 * @property int $id
 * @property int $semester
 * @property string $name
 * @property User $professors
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

  /**
   * @return BelongsToMany
   */
    public function professors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_subjects', 'subject_id', 'user_id');
    }
}
