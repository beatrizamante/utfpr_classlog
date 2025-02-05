<?php

namespace App\Controllers;

use App\Models\Block;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

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
                echo json_encode(['error' => 'Erro ao salvar']);
            }
        } else {
            echo json_encode(['error' => 'Erro ao criar']);
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
        $block->save();
        echo json_encode(['success' => $block->name]);
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();
        $block = Block::findById($params['id']);
        $block->destroy();

        echo json_encode(['success' => 'deletado com sucesso']);
    }
}
