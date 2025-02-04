<?php

namespace App\Controllers;

use App\Models\UserSubjects;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

use function array_map;
use function json_encode;

class UserSubjectsController extends Controller
{
    public function index(): void
    {
        $userSubjects = UserSubjects::all();
        $userSubjectsArray = array_map(function ($userSubject) {
            return [
              'id' => $userSubject->id,
              'user' => [
                'user_id' => $userSubject->user->id,
                'user_name' => $userSubject->user->name,

              ],
              'subject' => [
                'subject_id' => $userSubject->subject->id,
                'subject_name' => $userSubject->subject->name,
                'subject_semester' => $userSubject->subject->semester,
              ]
            ];
        }, $userSubjects);

        echo json_encode(['data' => $userSubjectsArray]);
    }
    public function addSubjectToProfessor(Request $request): void
    {

        $params = $request->getBody();

        $userSubject = new UserSubjects($params);
        if ($userSubject->isValid()) {
            if ($userSubject->save()) {
                $array =  [
                'id' => $userSubject->id,
                'user_id' => $userSubject->user_id,
                'subject_id' => $userSubject->subject_id,
                ];

                echo json_encode(['success' => $array]);
            } else {
                echo json_encode(['error' => 'Erro ao salvar']);
            }
        } else {
            echo json_encode(['error' => $userSubject->errors]);
        }
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $subject = UserSubjects::findById($params['id']);
        $subject->destroy();
    }
}
