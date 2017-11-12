<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class ModifiableBaseEntity extends BaseEntity
{
    /** @var string $type */
    protected $type           = 'Modifiable';

    protected $endWithBracket = true;

    /** @var string[] */
    protected $modifiers = [];

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

        parent::parse($tokens, $tokenizer, $additionalData);
    }

    public function hasModifier($modifier)
    {
        return in_array($modifier, $this->modifiers);
    }

    public function isPublic()
    {
        return $this->hasModifier('public');
    }

    public function isPrivate()
    {
        return $this->hasModifier('private');
    }

    public function isProtected()
    {
        return $this->hasModifier('protected');
    }

    public function isAbstract()
    {
        return $this->hasModifier('abstract');
    }
}
