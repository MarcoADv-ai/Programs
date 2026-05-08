<?php

namespace App\Http\Controllers;

use App\Models\ItemDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ItemDBController extends Controller
{
    private function searchLuaById($searchId)
    {
        // Lee el contenido del archivo
        $filePath = storage_path('ROChargen/Client/lua files/datainfo/itemInfo_default.lua');
        $luaContent = file_get_contents($filePath);

        $pattern = '/\[' . preg_quote($searchId, '/') . '\]\s*=\s*\{(.*?)\n\s*\},/s';

        if (preg_match($pattern, $luaContent, $matches)) {
            $block = $matches[1];

            $dataPattern = '/(\w+)\s*=\s*(\{.*?\}|"[^"]*"|[^,]+),?/s';
            preg_match_all($dataPattern, $block, $dataMatches, PREG_SET_ORDER);

            $result = [];
            foreach ($dataMatches as $match) {
                $key = $match[1];
                $value = trim($match[2]);

                // Verifica si el valor es un objeto anidado o una lista
                if (substr($value, 0, 1) === '{') {
                    $value = $this->parseNestedBlock($value);
                } else {
                    $value = trim($value, '"');
                }

                $result[$key] = $value;
            }

            return $result;
        }

        return null;
    }

    private function parseNestedBlock($block)
    {
        // Elimina los corchetes externos
        $block = trim($block, '{}');

        if (strpos($block, '=') === false) {
            // Es una lista
            $items = array_map('trim', explode(',', $block));
            return array_map(function ($item) {
                return trim($item, '"');
            }, $items);
        } else {
            $dataPattern = '/(\w+)\s*=\s*(\{.*?\}|"[^"]*"|[^,]+),?/s';
            preg_match_all($dataPattern, $block, $dataMatches, PREG_SET_ORDER);

            $nestedResult = [];
            foreach ($dataMatches as $dataMatch) {
                $key = $dataMatch[1];
                $value = trim($dataMatch[2]);

                if (substr($value, 0, 1) === '{') {
                    $value = $this->parseNestedBlock($value);
                } else {
                    $value = trim($value, '"');
                }

                $nestedResult[$key] = $value;
            }

            return $nestedResult;
        }
    }

    private function getTotal($table, $nameid)
    {
        return DB::table($table)
            ->select(DB::raw('COALESCE(SUM(`amount`), 0) AS total'))
            ->whereIn('nameid', [$nameid])
            ->orWhere('card0', $nameid)
            ->first()
            ->total;
    }


    public function index(Request $request)
    {
        $query = $request->get('q');
        $items = ItemDB::where('weight', '<>', null);

        if ($query) {
            $items = $items->where(function ($q) use ($query) {
                $q->where('id', $query)
                    ->orWhere('name_english', 'LIKE', '%' . $query . '%');
            });
        }

        $items = $items->paginate(25);

        $items->getCollection()->transform(function ($item) {
            $luaData = $this->searchLuaById($item->id);
            $totalCartInventory = $this->getTotal('cart_inventory', $item->id);
            $totalGuildStorage = $this->getTotal('guild_storage', $item->id);
            $totalInventory = $this->getTotal('inventory', $item->id);
            $totalStorage = $this->getTotal('storage', $item->id);

            $item->total = $totalInventory + $totalCartInventory + $totalStorage + $totalGuildStorage;

            if ($luaData) {
                $item->displayName = $luaData['identifiedDisplayName'] ?? '';
                $item->description = $luaData['identifiedDescriptionName'] ?? '';
                $item->count = $item->total;
            }

            return $item;
        });

        return Inertia::render('Information/ItemDB', ['items' => $items]);
    }
}
