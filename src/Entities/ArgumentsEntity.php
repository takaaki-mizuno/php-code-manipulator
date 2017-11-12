<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class ArgumentsEntity extends ModifiableBaseEntity
{
    /** @var string $type */
    protected $type = 'Arguments';

    protected $arguments = [];

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
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            if ($tokenizer->checkToken($token, ',') || $tokenizer->checkToken($token, T_WHITESPACE)) {
                $this->addContent($token);
            } elseif ($tokenizer->checkToken($token, ')')) {
                $this->addContent($token);

                return;
            } else {
                $tokenizer->back();
                $entity = new ArgumentEntity(null, $tokenizer);
                $this->addContent($entity);
                $this->arguments[] = $entity;
            }
        }
    }
}
