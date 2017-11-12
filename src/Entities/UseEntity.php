<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class UseEntity extends BaseEntity
{
    protected $type = 'Use';

    protected $classes = [];

    /**
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    protected function parse($tokens, &$tokenizer = null, $additionalData)
    {
        $this->addContents($tokens);
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            $this->addContent($token);
            if ($tokenizer->checkToken($token, ';')) {
                $this->classes = explode(',', $this->name);

                return;
            } elseif (!$tokenizer->checkToken($token, T_WHITESPACE)) {
                $this->name .= $tokenizer->getTokenContent($token);
            }
        }
    }
}
