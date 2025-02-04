<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\AdminPopulate;
use Database\Populate\RolesPopulate;
use Database\Populate\SchedulesPopulate;

Database::migrate();
RolesPopulate::populate();
AdminPopulate::populate();
SchedulesPopulate::populate();

