<?php

namespace App\ROChargen\core;

use App\ROChargen\loaders\Grf;
use App\ROChargen\core\Debug;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class Client
{
	/**
	 * Define the client dir
	 */
	static private $grfs       = array();
	static public $AutoExtract = false;

	/**
	 * Load on init
	 */
	static public function init()
	{
		Debug::write('Client init...', 'title');
		$path = storage_path('ROChargen/Client/');
		$data_ini = Config::get('rochargen.ini_file');	
		$fileExsist = Storage::disk('rochargen')->exists('Client/'.$data_ini);

		// Load GRFs from DATA.INI
		if (!empty($data_ini) && $fileExsist) {
			Debug::write('Loading "' . $data_ini . '" file...', 'info');
			// Setup GRF context
			$data_ini = parse_ini_file($path . $data_ini, true);
			$grfs     = array();
			foreach ($data_ini['Data'] as $index => $grf_filename) {
				self::$grfs[$index] = new Grf($path . $grf_filename);
				self::$grfs[$index]->filename = $grf_filename;
				$grfs[] = $grf_filename;
			}
			return;
		}

		Debug::write('File "' . $data_ini . '" isn\'t load : not set, not found, or not readable in "' . $path . '".', 'error');
	}



	/**
	 * Get a file from client, search it on data dir first, and on grfs.
	 */
	public static function getFile($path)
	{
		
		Debug::write('Trying to find file "' . $path . '"...', 'title');
		
		$local_path  = str_replace('\\', '/', $path);
		$grf_path    = str_replace('/', '\\', $path);

		// Read data first
		if (Storage::disk('rochargen')->exists($local_path)) {
			Debug::write('Find at "' . $local_path . '"', 'success');
			return Storage::disk('rochargen')->path($local_path);
		}

		// Search in GRFS
		Debug::write('File not found in data folder.');
		if (count(self::$grfs)) {
			Debug::write('Searching in GRFs...');
		}

		foreach (self::$grfs as $grf) {

			// Load GRF just if needed
			if (!$grf->loaded) {
				Debug::write('Loading GRF file "' . $grf->filename . '"...', 'info');
				$grf->load();
			}

			// If file is found
			if ($grf->getFile($grf_path, $content)) {

				Debug::write('Search in GRF "' . $grf->filename . '", found.', 'success');

				// Auto Extract GRF files ?
				if (self::$AutoExtract) {

					Debug::write('Saving file to data folder...', 'info');

					$current_path = '';
					$directories  = explode('/', $path);
					array_pop($directories);

					// Creating directories
					foreach ($directories as $dir) {
						$current_path .= $dir . DIRECTORY_SEPARATOR;

						if (!Storage::disk('rochargen')->exists($current_path)) {
							Storage::disk('rochargen')->makeDirectory($current_path);
						}
					}
					// Saving file
					dd($local_path);
					Storage::disk('rochargen')->put($local_path, $content);
					return Storage::disk('rochargen')->path($local_path);
				}

				return "data://application/octet-stream;base64," .  base64_encode($content);
			}

			Debug::write('Search in GRF "' . $grf->filename . '", fail.');
		}

		Debug::write('File not found...', 'error');
		return false;
	}


	/**
	 * Search files (only work in GRF)
	 */
	static public function search($filter)
	{
		$out = array();

		foreach (self::$grfs as $grf) {

			// Load GRF only if needed
			if (!$grf->loaded) {
				$grf->load();
			}

			// Search
			$list = $grf->search($filter);

			// Merge
			$out  = array_unique(array_merge($out, $list));
		}

		//sort($out);
		return $out;
	}
}
