<?php

namespace App\Http\Controllers;

use App\Models\Block;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlockController extends Controller
{
    // Metodo para generar un hash: 
    public function generateHash($data, $previousBlock)
    {
        $content = json_encode([$data, $previousBlock]);
        $hash = hash('gost-crypto', $content);
        return $hash;
    }

    // Metodo para generar un bloque 'Genesis': 
    // public function generateGenesis($request)
    // {
    //     $datas = json_decode($request);
    //     $file          = $datas['file']->store('files');
    //     $data          = json_encode(['data_user' => $datas['data_user'], 'file' => $file]);

    //     $previousBlock = json_encode(['key_previous_block' => "", 'hash_previous_block' => ""]);

    //     $hash          = $this->generateHash(data: $data, previousBlock: $previousBlock);

    //     try{

    //         $currentDate = new DateTime();

    //         Block::create(['hash' => $hash,
    //                         'data' => $data,
    //                         'previousBlock' => $previousBlock,
    //                         'created' => $currentDate->format('Y-m-d H:i:s')]);

    //         return response(content: ['generate' => true], status: 201);

    //     }catch(Exception $e){
    //         return response(content: ['generate' => false, 'error' => $e->getMessage()], status: 500);
    //     }

    // }

    public function generateGenesis(Request $request)
    {
        $data          = json_encode(['data_user' => $request->input('data_user'), 'file' =>  $request->input('file')]);

        $previousBlock = json_encode(['public_key_previous_block' => "", 'private_key_previous_block' => ""]);

        $hash          = $this->generateHash(data: $data, previousBlock: $previousBlock);

        try{

            $currentDate = new DateTime();

            Block::create(['hash' => $hash,
                            'data' => $data,
                            'previous_block' => $previousBlock,
                            'created' => $currentDate->format('Y-m-d H:i:s')]);

            return response(content: ['generate' => true], status: 201);

        }catch(Exception $e){
            return response(content: ['generate' => false, 'error' => $e->getMessage()], status: 500);
        }

    }

    public function generateBlock(Request $request)
    {
        $data          = json_encode(['data_user' => $request->input(key: 'data_user'), 'file' => $request->input('file')]);
        
        $previousBlock = ['public_key_previous_block' => $request->input(key: 'public_key_previous_block'), 'private_key_previous_block' => $request->input(key: 'private_key_previous_block')];
        
        $hash          = $this->generateHash(data: $data, previousBlock: $previousBlock);

        $previousHash  = $previousBlock['private_key_previous_block'];
        $previousKey   = $previousBlock['public_key_previous_block'];

        $model = Block::where('hash', $previousHash);

        $validateChain = $model->first();

        if($validateChain){

            if(($validateChain['public_key'] == $previousKey) && ($validateChain['hash'] == $previousHash)){

                $blocks = [json_decode($validateChain['previous_block'])];
    
                $blockHistory = [];
    
                if(is_array($blocks[0])){
    
                    foreach($blocks[0] as $register){
                    
                        $blockHistory[] = $register;

                    }
    
                }
    
                array_push($blockHistory, $previousBlock);
    
                try{
    
                    $currentDate = new DateTime();
            
                    Block::create(['hash' => $hash,
                                    'data' => $data,
                                    'previous_block' => json_encode($blockHistory),
                                    'created' => $currentDate->format('Y-m-d H:i:s')]);
            
                
                    return response(content: ['generate' => true], status: 201);
                
                }catch(Exception $e){
                    return response(content: ['generate' => false, 'error' => $e->getMessage()], status: 500);
                }
    
            }else{
                return response(content: ['generate' => false, 'error' => 'No existe esa cadena.'], status: 404);
            }

        }else{
            return response(content: ['generate' => false, 'error' => 'No existe ese bloque.'], status: 404);
        }

    }

    public function getBlocks()
    {
        $model = Block::select('public_key', 'hash as private_key')->get();
        return response(content: ['query' => true, 'blocks' => $model], status:200);
    }

    public function getChain($public_key, $private_key)
    {
        $model = Block::select('previous_block as chain')->where('public_key', $public_key)->where('hash', $private_key)->orderBy('chain', 'desc')
        ;

        $validateChain = $model->first();

        if($validateChain){

            return response(content: ['query' => true, 'chain' => json_decode($validateChain['chain'])], status: 200);

        }else{
            return response(content: ['query' => false, 'error' => 'No existe esa cadena.'], status: 404);
        }

    }

    // Metodo para obtener un bloque especifico: 
    public function getBlock($public_key)
    {
        $model = Block::select('hash as private_key')->where('public_key', $public_key);

        $validateBlock = $model->first();

        if($validateBlock){

            return response(content: ['query' => true, 'block' => $validateBlock], status: 200);

        }else{
            return response(content: ['query' => false, 'error' => 'No existe ese bloque.'], status: 404);
        }
    }

    // Metodo para obtener los datos de un contenedor: 
    public function getData($private_key)
    {
        $model = Block::select('data')->where('hash', $private_key);

        $validateBlock = $model->first();

        if($validateBlock){

            return response(content: ['query' => true, 'data' => $validateBlock], status: 200);

        }else{
            return response(content: ['query' => false, 'error' => 'No existe ese bloque.'], status: 404);
        }
    }
    
}
