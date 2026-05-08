<?php

namespace App\Http\Controllers;

use App\ROChargen\Controllers\Character;
use Exception;
use Illuminate\Http\Request;
use App\ROChargen\core\Client;
use Illuminate\Support\Facades\Log;
use App\ROChargen\Controllers\CharacterHead;
use App\ROChargen\core\Debug;
use Illuminate\Support\Facades\Config;

class ChargenController extends Controller
{
    public function character_head($char_id)
    {
        if(Config::get('rochargen.useROChargen') == false) return;
        try {
            Client::init();
            $characterHead = new CharacterHead();
            $characterHead->process($char_id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Debug::write($e->getMessage(), 'error');
        }
    }

    public function character($char_id)
    {
        if(Config::get('rochargen.useROChargen') == false) return;
        try {
            Client::init();
            $character = new Character();
            $character->process($char_id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Debug::write($e->getMessage(), 'error');
        }
    }
}
