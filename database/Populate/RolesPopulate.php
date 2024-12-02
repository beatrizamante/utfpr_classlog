<?php

namespace Database\Populate;


use App\Models\Roles;

class RolesPopulate
{
  public static function populate()
  {
    $admin = ['name' => 'admin'];
    $professor = ['name' => 'professor'];

    $adminRole = new Roles($admin);
    $adminRole->save();

    $professorRole = new Roles($professor);
    $professorRole->save();
  }
}
