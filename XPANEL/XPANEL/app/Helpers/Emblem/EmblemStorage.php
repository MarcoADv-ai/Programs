<?php

namespace App\Helpers\Emblem;

class EmblemStorage
{
    private $tmpDir = 'storage/cache/tmp_emblem/';
    private $guildId;
    private $file;
    private $emblemData;
    private $emblemLen;

    public function __construct($guildId = 0, $emblemData = "", $emblemLen = 0)
    {
        $this->guildId = $guildId;
        $this->emblemData = $emblemData;
        $this->emblemLen = $emblemLen;
        $this->file = $this->tmpDir . $this->guildId . '.png';
    }

    public function createDirIfNotExists()
    {
        if (!is_dir($this->tmpDir))
            mkdir($this->tmpDir, 0700, true);

    }

    public function fileExists()
    {
        if (file_exists($this->file) && (time() - filemtime($this->file)) < (12 * 60))
            return $this->file;
        else
            return false;
    }

    public function saveImageToFile()
    {
        if (empty($this->emblemLen))
            return 'X';

        $data  = @gzuncompress(pack('H*', $this->emblemData));

        if (empty($data))
            return 'X';

        $im = new EmblemImageHandler;
        $image = $im->imgCreateFromBmpString($data);

        imagepng($image, $this->file);

        return $this->file;
    }
}
