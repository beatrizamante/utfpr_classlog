<?php

namespace Database\Populate;


use App\Enums\RolesEnum;
use App\Models\Roles;
use App\Models\User;

class AdminPopulate
{
  public static function populate()
  {
    $admin = [
      'name' => 'admin',
        'university_registry' => '11111111',
      'password' => 'admin',
      'password_confirmation' => 'admin'
    ];
    $admin['role_id'] = RolesEnum::ADMIN->value;
    $admin = new User($admin);
    $admin->save();
  }
}
