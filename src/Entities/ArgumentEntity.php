<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class ArgumentEntity extends ModifiableBaseEntity
{
    /** @var string $type */
    protected $type = 'Argument';

    /**
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    protected function parse($tokens, &$tokenizer = null, $additionalData)
    {
        if (is_array($tokens) && count($tokens) > 0) {
            $this->addContents($tokens);
        }
        $parentheses = 0;
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_VARIABLE:
                        $this->name = $token[1];
                        break;
                }
            } else {
                switch ($token) {
                    case '(':
                        $parentheses++;
                        break;
                    case ')':
                        if ($parentheses > 0) {
                            $parentheses--;
                        } else {
                            $tokenizer->back();

                            return;
                        }
                        break;
                }
            }
            $this->addContent($token);
        }
    }

    protected function parseArguments()
    {
    }
}
