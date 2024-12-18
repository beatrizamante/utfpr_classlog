<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Models\UserSubjects;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function array_map;
use function array_push;
use function json_encode;

class UserSubjectsController extends Controller
{
    public function index(): void
    {
        $userSubjects = UserSubjects::all();
        $userSubjectsArray = array_map(function ($userSubject) {
            return [
            'id' => $userSubject->id,
            'user_id' => $userSubject->user_id,
            'subject_id' => $userSubject->subject_id,
            ];
        }, $userSubjects);

        echo json_encode(['data' => $userSubjectsArray]);
    }
    public function addSubjectToProfessor(Request $request): void
    {
        $params = $request->getBody();
        $userSubject = new UserSubjects($params);

        $userSubject->isValid();

        $userSubject->save();

        echo $userSubject;
    }
}
