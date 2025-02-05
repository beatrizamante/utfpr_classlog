<?php

namespace App\Controllers;

use App\Models\Block;
use App\Models\ClassRoom;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

use function is_null;
use function json_encode;

class ClassRoomController extends Controller
{
    public function index(): void
    {
        $allClassRooms = ClassRoom::all();
        $classRoomsArray = array_map(function ($room) {
            return [
            'id' => $room->id,
            'block_id' => $room->block_id,
            'block_name' => $room->block->name,
            'name' => $room->name,
            ];
        }, $allClassRooms);

        echo json_encode($classRoomsArray);
    }

    public function create(Request $request): void
    {
        $params = $request->getBody();
        $classroom = new ClassRoom($params);
        if ($classroom->isValid()) {
            if ($classroom->save()) {
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
        $classroom = ClassRoom::findById($params['id']);

        if (is_null($classroom)) {
            echo json_encode(['error' => 'sala não encontrado']);
            return;
        }

        $response = [
          'id' => $classroom->id,
          'block_id' => $classroom->block_id,
          'block_name' => $classroom->block->name,
          'name' => $classroom->name,
        ];

        echo json_encode(['data' => $response]);
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $body = $request->getBody();
        $classroom = ClassRoom::findById($params['id']);
        if (is_null($classroom)) {
            echo json_encode(['error' => 'bloco não encontrado']);
            return;
        }
        $classroom->name = $body['name'];
        $classroom->save();

        $classroomArray = [
        'id' => $classroom->id,
        'block_id' => $classroom->block_id,
        'block_name' => $classroom->block->name,
        'name' => $classroom->name,
        ];
        echo json_encode(['success' => $classroomArray]);
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);
        $block->destroy();

        echo json_encode(['success' => 'deletado com sucesso']);
    }
}
