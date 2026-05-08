<?php

namespace App\Http\Controllers;

use App\Models\StorageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StorageRequestController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request->input('items');
        $master_id = auth()->user()->id;
        
        if(StorageRequest::where('master_id', $master_id)->count() > 0)
        {
            return response()->json(['message' => 'You already have a pending request.'], 400);
        }

        foreach ($items as $item) {
            $item_id = $item['id'];
            $quantity = $item['quantity'];
            $refine = $item['refine'];
            $type = $item['type'];

            if($item['quantity'] > 30000)
                return response()->json(['message' => 'You can\'t request more than 30,000 of the same item.'], 400);


            $storage_request = new \App\Models\StorageRequest();
            $storage_request->master_id = $master_id;
            $storage_request->item_id = $item_id;
            $storage_request->qty = $quantity;
            $storage_request->refine = $refine;
            $storage_request->type = $type;
            $storage_request->save();
        }

        return response()->json(['message' => 'Request sent successfully.'], 200);
    }

    public function accept_request(Request $request)
    {
        $item_data = $request->input('item_data');
        $item_type = $item_data['type'];
        $item_id = $item_data['item_id'];
        $item_qty = $item_data['qty'];
        $item_refine = $item_data['refine'];
        $master_id = $item_data['master_id'];
        $storage_request_id = $item_data['id'];
        $storage_count = DB::table('master_storage')->where('master_id', $master_id)->count();
        
        switch($item_type){
            case 'Etc':
            case 'Delayconsume':
            case 'Usable':
            case 'Cash':
            case 'Healing':
            case 'Ammo':
                $exists = DB::table('master_storage')->where('master_id', $master_id)->where('nameid', $item_id)->first();
                $total_storage = $storage_count + 1; // +1 because we're adding a new stack item 
                $exists_amount = $exists->amount ?? 0;
                $new_amount = $exists_amount + $item_qty;
                
                if($total_storage > 850)
                    return response()->json(['message' => 'You can\'t have more than 850 items in your storage.'], 400);

                if($new_amount > 30000)
                    return response()->json(['message' => 'You can\'t have more than 30,000 of the same item.'], 400);
                    
                if($exists)
                    DB::table('master_storage')->where('master_id', $master_id)->where('nameid', $item_id)->update(['amount' => $new_amount]);
                else                    
                    DB::table('master_storage')->insert([
                        'master_id' => $master_id,
                        'nameid' => $item_id,
                        'amount' => $item_qty,
                        'identify' => 1,
                        'refine' => 0,
                    ]);
                
                break;

            // Weapons, Armors,
            default:
                $new_amount = $storage_count + $item_qty;

                if($new_amount > 850)
                    return response()->json(['message' => 'You can\'t have more than 850 items in your storage.'], 400);


                for($i = 0; $i < $item_qty; $i++){
                    DB::table('master_storage')->insert([
                        'master_id' => $master_id,
                        'nameid' => $item_id,
                        'amount' => 1,
                        'identify' => 1,
                        'refine' => $item_refine,
                    ]);
                }

        }
        DB::table('storage_request')->where('id', $storage_request_id)->delete();

        return response()->json(['message' => 'Request accepted successfully.'], 200);
    }

    public function destroy($id){
        $storage_request = StorageRequest::find($id);
        $storage_request->delete();

        return response()->json(['message' => 'Request deleted successfully.'], 200);
    }
}
