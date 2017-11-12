<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class TokenEntity extends BaseEntity
{
    /** @var string $type */
    protected $type = 'Token';

    protected $tokens;

    public function stringify()
    {
        $result = '';
        foreach ($this->tokens as $content) {
            $result .= is_array($content) ? $content[1] : $content;
        }

        return $result;
    }

    /**
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    protected function parse($tokens, &$tokenizer = null, $additionalData)
    {
        $this->tokens = $tokens;
    }
}
