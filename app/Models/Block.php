<?php

namespace App\Models;

use App\Services\ImageUpload;
use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

use function array_map;

/**
 * @property int $id
 * @property string $name
 * @property int $role_id
 */
class Block extends Model
{
    protected static string $table = 'blocks';
    protected static array $columns = ['name', 'photo'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::uniqueness('name', $this);
    }

    public function photo(): ImageUpload
    {
      return new ImageUpload($this);
    }
}
