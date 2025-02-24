<?php

namespace App\Controllers;

use App\Models\Block;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

use function array_map;
use function is_null;
use function json_encode;

class BlockController extends Controller
{
    public function index(): void
    {
        $allBlocks = Block::all();
        $blocksArray = array_map(function ($block) {
            return [
            'id' => $block->id,
            'name' => $block->name,
            'photo' => $block->photo()->path(),
            ];
        }, $allBlocks);

        echo json_encode($blocksArray);
    }

    public function create(Request $request): void
    {
        $image = ($_FILES['photo'] ?? null);
        $params = $request->getBody();
        unset($params['PHPSESSID']);
        $block = new Block($params);

        if ($block->isValid()) {
            if ($block->save()) {
                if (!is_null($image)) {
                    $block->photo()->update($image);
                }

                $response = [
                'id' => $block->id,
                'name' => $block->name,
                'photo' => $block->photo()->path(),
                ];


                echo json_encode(['success' => 'Criado com sucesso', 'data' => $response]);
            } else {
                echo json_encode(['error' => $block->getErrors()]);
            }
        } else {
            echo json_encode(['error' => $block->getErrors()]);
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);

        if (is_null($block)) {
            echo json_encode(['error' => 'bloco não encontrado']);
            return;
        }

        $response = [
          'id' => $block->id,
          'name' => $block->name,
          'photo' => $block->photo()->path(),
        ];

        echo json_encode(['data' => $response]);
    }

    public function update(Request $request): void
    {


        $params = $request->getParams();
        $body = $request->getBody();
        $block = Block::findById($params['id']);
        if (is_null($block)) {
            echo json_encode(['error' => 'bloco não encontrado']);
            return;
        }
        if (isset($body['name'])) {
            $block->name = $body['name'];
        }
        if ($block->isValid()) {
            $block->save();

            echo json_encode(['success' => $block->name]);
        } else {
            echo json_encode(['error' => $block->getErrors()]);
        }
    }

    public function imageUpdate(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);
        $image = ($_FILES['photo']);
        if (!is_null($image)) {
            $block->photo()->update($image);
        }

        echo json_encode(['success' => $block->photo()->path()]);
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);
        $block->destroy();

        echo json_encode(['success' => 'deletado com sucesso']);
    }
}
