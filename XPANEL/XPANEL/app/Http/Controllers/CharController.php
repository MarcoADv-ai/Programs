<?php

namespace App\Http\Controllers;

use App\Helpers\Logs\LogMasterAccount;
use App\Helpers\Logs\LogWriter;
use App\Models\Char;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CharController extends Controller
{

    /**
     * Update the character information using char_id data.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param $char_id 
     */
    public function update(Request $request, $char_id)
    {
        $resetType = $request->resetType;
        $char = Char::findOrFail($char_id);
        $log = new LogWriter(new LogMasterAccount());

        switch ($resetType) {
            case 'position':
                $saves = Char::select('save_x', 'save_y', 'save_map')->where('char_id', $char->char_id)->first();
                Char::where('char_id', $char->char_id)->update(['last_x' => $saves['save_x'], 'last_y' => $saves['save_y'], 'last_map' => $saves['save_map']]);
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Character ' . $char_id . ' was reset position',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),

                ];
                $log->writeInTable($data);
                return Redirect::back()->with('success', 'Character ' . $char->name . ' position reset successfully');
                break;

            case 'equipment':
                Char::where('char_id', $char->char_id)->update(['body' => 0, 'weapon' => 0, 'shield' => 0, 'head_top' => 0, 'head_mid' => 0, 'head_bottom' => 0, 'robe' => 0]);
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Character ' . $char_id . ' was reset equipment',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),

                ];
                $log->writeInTable($data);
                return Redirect::back()->with('success', 'Character ' . $char->name . '  equipment reset successfully');
                break;

            case 'appearence':
                Char::where('char_id', $char->char_id)->update(['hair' => 0, 'hair_color' => 0, 'clothes_color' => 0]);
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Character ' . $char_id . ' was reset appearence',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),

                ];
                $log->writeInTable($data);
                return Redirect::back()->with('success', 'Character ' . $char->name . '  appearence reset successfully');
                break;

            default:
                return Redirect::back()->with('error', 'Invalid option');
                break;
        }
    }
}
