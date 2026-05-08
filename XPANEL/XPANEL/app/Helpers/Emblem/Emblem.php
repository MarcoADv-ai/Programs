<?php

namespace App\Helpers\Emblem;

use App\Models\Guild;

class Emblem
{
    private $guildId;
    private $emblemData;
    private $emblemLen;

    public function __construct($guildId, $emblemData, $emblemLen)
    {
        $this->guildId = $guildId;
        $this->emblemData = $emblemData;
        $this->emblemLen = $emblemLen;
    }

    public function getEmblem()
    {
        $emblemStorage = new EmblemStorage($this->guildId, $this->emblemData, $this->emblemLen);
        $emblemStorage->createDirIfNotExists();

        if ($emblemStorage->fileExists())
            return $emblemStorage->fileExists();
        else
            return $emblemStorage->saveImageToFile();
    }
}
