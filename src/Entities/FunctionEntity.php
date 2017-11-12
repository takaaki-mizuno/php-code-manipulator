<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class FunctionEntity extends ModifiableBaseEntity
{
    /** @var string $type */
    protected $type           = 'Function';

    protected $endWithBracket = true;

    protected function processContent($data)
    {
        if (empty($this->name) && is_array($data) && $data[0] === T_STRING) {
            $this->name = $data[1];
        }

        return $data;
    }

    /**
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    protected function parse($tokens, &$tokenizer = null, $additionalData)
    {
        if (array_key_exists('modifiers', $additionalData)) {
            $this->modifiers = $additionalData['modifiers'];
        }

        if (is_array($tokens) && count($tokens) > 0) {
            $this->addContents($tokens);
        }
        $tokens      = [];
        $depth       = 0;
        $parentheses = 0;
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            if (is_array($token)) {
                switch ($token[0]) {
                    default:
                        $tokens[] = $token;
                }
            } else {
                switch ($token) {
                    case '{':
                        $depth++;
                        break;
                    case '}':
                        if ($depth > 0) {
                            $depth--;
                        }
                        break;
                    case '(':
                        if ($depth === 0 && $parentheses === 0) {
                            $entity = new ArgumentsEntity(array_merge($tokens, [$token]), $tokenizer);
                            $this->addContent($entity);
                            $tokens = [];
                            break;
                        } else {
                            $parentheses++;
                        }
                        break;

                    case ')':
                        if ($parentheses > 0) {
                            $parentheses--;
                        }
                        break;
                }
                $tokens[] = $token;
                if ($token === '}' && $depth == 0 && $this->endWithBracket) {
                    $this->addContents($tokens);

                    return;
                }
            }
        }
        $this->addContents($tokens);
    }
}
