<?php

namespace App\ROChargen\Controllers;

use App\Models\Char;
use App\ROChargen\core\Cache;
use App\ROChargen\render\CharacterRender;
use Illuminate\Support\Facades\Config;

class Character
{

	/**
	 * Load database, specify where to cache things
	 */
	public function __construct()
	{
		Cache::setNamespace('character');
	}


	/**
	 * Process entry
	 */
	public function process($pseudo, $action = -1, $animation = -1)
	{
		$cache = Config::get('rochargen.cache_time') ?? 15 * 60;

		header('Content-type:image/png');
		header('Cache-Control: max-age=' . $cache . ', public');

		Cache::setFilename($pseudo . ".png");
		$content    = "";

		// Load the cache file ?
		if (Cache::get($content)) {
			die($content);
		}

		// Find and render
		$data = $this->getPlayerData($pseudo);
		$this->render($data, $action, $animation);

		// Cache
		Cache::save();
	}


	/**
	 * Get player data from SQL
	 */
	private function getPlayerData($pseudo)
	{
		$data = Char::select([
			'class', 'clothes_color',
			'hair', 'hair_color',
			'head_top', 'head_mid', 'head_bottom',
			'robe', 'weapon', 'shield',
			'option', 'char.sex'
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
	private function render($data, $action, $animation)
	{
		// Load Class and set parameters
		$chargen                 =  new CharacterRender();
		$chargen->action         =  $action    == -1 ? CharacterRender::ACTION_ATTACK   : intval($action);
		$chargen->direction      =  $animation == -1 ? CharacterRender::DIRECTION_SOUTHEAST : intval($animation);
		$chargen->body_animation =  4;
		$chargen->doridori       =  0;
		// Generate Image
		$chargen->loadFromSqlData($data);
		$img  = $chargen->render();

		imagepng($img);
	}
}
