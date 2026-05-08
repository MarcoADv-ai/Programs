<?php
namespace App\ROChargen\core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

final class Cache
{
	static private $directory = "";
	static private $filename  = "";


	/**
	 * Find a file in cache
	 */
	public static function get(&$content) : bool
	{
		$path = 'public/cache/rochargen/'.self::$directory . '/' . self::$filename;

		if (Storage::exists($path)) {
			// Cache not disable
			if (Storage::lastModified($path) + Config::get('rochar.cache_time') > time()) {
				$content = Storage::get($path);
				return true;
			}
	
			Storage::delete($path);
		}
	
		return false;
	}


	/**
	 * Set a directory where to save files
	 */
	static public function setNamespace($name) : void
	{
		self::$directory = $name;
	}


	/**
	 * Set a filename
	 */
	static public function setFilename($name) : void
	{
		self::$filename = $name;
	}


	/**
	 * Store a file in cache
	 */


	public static function save() : void
	{
		$cache = Config::get('rochargen.cache_time');
		// Cache not disable
		if ($cache > 0) {
			$path = '/cache/rochargen/'.self::$directory . '/' . self::$filename;
			$content = ob_get_contents();
			// Saving content
			Storage::disk('public')->put($path, $content);
		}
	}
}
