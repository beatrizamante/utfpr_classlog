<?php

namespace App\Controllers;

use App\Models\Block;
use App\Models\Subject;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Exception;

use function array_map;
use function is_null;
use function json_encode;

class SubjectController extends Controller
{
    public function index(): void
    {
        $allSubjects = Subject::all();
        $classRoomsArray = array_map(function ($subject) {
    /** @var \App\Models\User[] $professors */
            $professors = $subject->professors;
            return [
                  'id' => $subject->id,
                  'name' => $subject->name,
                  'semester' => $subject->semester,
                  'professors' => array_map(function ($professor) {
                    return [$professor->name];
                  }, $professors)
            ];
        }, $allSubjects);

        echo json_encode($classRoomsArray);
    }


    public function create(Request $request): void
    {
        $params = $request->getBody();
        $subject = new Subject($params);
        if ($subject->isValid()) {
            if ($subject->save()) {
                echo json_encode(['success' => 'Criado com sucesso']);
            } else {
                echo json_encode(['error' => 'Erro ao salvar']);
            }
        } else {
            echo json_encode(['error' => 'Erro ao criar']);
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $subject = Subject::findById($params['id']);

        if (is_null($subject)) {
            echo json_encode(['error' => 'sala não encontrado']);
            return;
        }

        $response = [
        'id' => $subject->id,
        'name' => $subject->name,
          'semester' => $subject->semester,
        ];

        echo json_encode(['data' => $response]);
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $body = $request->getBody();
        $subject = Subject::findById($params['id']);
        if (is_null($subject)) {
            echo json_encode(['error' => 'bloco não encontrado']);
            return;
        }
        $subject->name = $body['name'];
        $subject->semester = $body['semester'];
        $subject->save();

        $subjectArray = [
        'id' => $subject->id,
        'name' => $subject->name,
          'semester' => $subject->semester,
        ];
        echo json_encode(['success' => $subjectArray]);
    }

    public function destroy(Request $request): void
    {
        try {
            $params = $request->getParams();
            $subject = Subject::findById($params['id']);

            if (!$subject) {
                echo json_encode(['error' => 'Bloco não encontrado']);
                return;
            }

            $subject->destroy();

            echo json_encode(['success' => 'Deletado com sucesso']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
