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
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'encrypted_password', 'university_registry', 'role_id'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('university_registry', $this);

        Validations::uniqueness('university_registry', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByUniversityRegistry(string $ra): User | null
    {
        return User::findBy(['university_registry' => $ra]);
    }

  /**
   * @return BelongsTo
   */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function roleName(): ?string
    {
        $role = $this->role()->get();
        return $role->name;
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}
