<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class NamespaceEntity extends BaseEntity
{
    protected $type = 'Namespace';

    /**
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    protected function parse($tokens, &$tokenizer = null, $additionalData)
    {
        $this->name = '';

        $this->addContents($tokens);
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            $this->addContent($token);
            if ($tokenizer->checkToken($token, ';')) {
                return;
            } elseif (!$tokenizer->checkToken($token, T_WHITESPACE)) {
                $this->name .= $tokenizer->getTokenContent($token);
            }
        }
    }
}
