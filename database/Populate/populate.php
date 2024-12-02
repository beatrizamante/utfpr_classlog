<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\AdminPopulate;
use Database\Populate\RolesPopulate;

Database::migrate();
RolesPopulate::populate();
AdminPopulate::populate();

