<?php

namespace App\ROChargen\Controllers;
use App\Models\Char;
use App\ROChargen\core\Cache;
use App\ROChargen\render\CharacterHeadRender;
use Illuminate\Support\Facades\Config;

class CharacterHead
{

	/**
	 * Load database, specify where to cache things
	 */
	public function __construct()
	{
		Cache::setNamespace('characterhead');
	}


	/**
	 * Process entry
	 */
	public function process($pseudo)
	{
		$cache = Config::get('rochargen.cache_time') ?? 15 * 60;

		header('Content-type:image/png');
		header('Cache-Control: max-age=' . $cache . ', public');
		
		Cache::setFilename($pseudo . ".png");
		$content    = "";

		if (Cache::get($content)) {
			die($content);
		}

		// Find and render
		$data = $this->getPlayerData($pseudo);
		$this->render($data);

		// Cache
		Cache::save();
	}


	/**
	 * Get player data from SQL
	 */
	private function getPlayerData($pseudo)
	{

		$data = Char::select([
			'hair', 'hair_color',
			'head_top', 'head_mid', 'head_bottom',
			'char.sex'
		])->where('char.name', $pseudo)->first()->toArray();
		
		// No character found ? Load a default character ?
		if (empty($data)) {
			
			// Store file, not needed to recalculate it each time
			Cache::setFilename("[notfound].png");
			$content    = "";

			if (Cache::get($content)) {
				die($content);
			}

			return array(
				"class"         =>  0,
				"clothes_color" =>  0,
				"hair"          =>  2,
				"hair_color"    =>  0,
				"head_top"      =>  0,
				"head_mid"      =>  0,
				"head_bottom"   =>  0,
				"robe"          =>  0,
				"weapon"        =>  0,
				"shield"        =>  0,
				"sex"           => "M"
			);
		}
		
		return $data;
	}


	/**
	 * Render avatar
	 */
	private function render($data)
	{
		// Load Sprites and set parameters
		$chargen                 =  new CharacterHeadRender();
		$chargen->direction      =  CharacterHeadRender::DIRECTION_SOUTHEAST;
		$chargen->doridori       =  2;

		// Generate Image
		$chargen->loadFromSqlData($data);
		$img  = $chargen->render();
		
		imagepng($img);
	}
}
