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
    public function generateGenesis(Request $request)
    {
        $file          = $request->file('files')->store('files');
        $data          = json_encode(['data_user' => $request->input('data_user'), 'file' => $file]);

        $previousBlock = json_encode(['key_previous_block' => "", 'hash_previous_block' => ""]);

        $hash          = $this->generateHash(data: $data, previousBlock: $previousBlock);

        try{

            $currentDate = new DateTime();

            Block::create(['hash' => $hash,
                            'data' => $data,
                            'previousBlock' => $previousBlock,
                            'created' => $currentDate->format('Y-m-d H:i:s')]);

            return response(content: ['generate' => true], status: 201);

        }catch(Exception $e){
            return response(content: ['generate' => false, 'error' => $e->getMessage()], status: 500);
        }

    }

    public function generateBlock(Request $request)
    {
        $file          = $request->file('files')->store('files');
        $data          = json_encode(['data_user' => $request->input(key: 'data_user'), 'file' => $file]);
        
        $previousBlock = ['key_previous_block' => $request->input(key: 'key_previous_block'), 'hash_previous_block' => $request->input(key: 'hash_previous_block')];
        
        $hash          = $this->generateHash(data: $data, previousBlock: $previousBlock);

        $previousHash  = $previousBlock['hash_previous_block'];
        $previousKey   = $previousBlock['key_previous_block'];

        $model = Block::where('hash', $previousHash);

        $validateChain = $model->first();

        if($validateChain){

            if(($validateChain['public_key'] == $previousKey) && ($validateChain['hash'] == $previousHash)){

                $blocks = [json_decode($validateChain['previousBlock'])];
    
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
                                    'previousBlock' => json_encode($blockHistory),
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
        return Block::all();
    }

    public function getChain($)


}
