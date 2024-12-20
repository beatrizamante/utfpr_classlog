<?php

namespace App\Controllers;

use App\Models\Block;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function is_null;
use function json_encode;
use function print_r;
use function toString;

class BlockController extends Controller
{
    public function index(): void
    {
        $allBlocks = Block::all();
        $blocksArray = array_map(function ($block) {
            return [
            'id' => $block->id,
            'name' => $block->attributes['name'],
            ];
        }, $allBlocks);

        echo json_encode($blocksArray);
    }

    public function create(Request $request): void
    {
        $params = $request->getBody();
        $block = new Block($params);

        if ($block->isValid()) {
            if ($block->save()) {
                echo json_encode(['success' => 'Criado com sucesso']);
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
        $block->name = $body['name'];


        if($block->isValid()){
          $block->save();
          echo json_encode(['success' => $block->name]);
        } else {
          echo json_encode(['error' => $block->getErrors()]);
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);
        $block->destroy();

        echo json_encode(['success' => 'deletado com sucesso']);
    }
}
