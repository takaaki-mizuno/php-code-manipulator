<?php
namespace TakaakiMizuno\PhpCodeManipulator;

use TakaakiMizuno\PhpCodeManipulator\Entities\FileEntity;

class Parser
{
    public function __construct()
    {
    }

    /**
     * @param $path
     *
     * @return FileEntity
     */
    public function parse($path)
    {
        $tokenizer = Tokenizer::fromFile($path);
        $file      = new FileEntity(null, $tokenizer);

        return $file;
    }
}
