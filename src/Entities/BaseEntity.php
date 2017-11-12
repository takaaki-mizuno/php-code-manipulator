<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;

class BaseEntity
{
    /** @var BaseEntity[] $contents */
    public $contents       = [];

    /** @var string $type */
    protected $type = 'Base';

    /** @var string $path */
    protected $name = '';

    protected $endWithBracket = false;

    /**
     * BaseEntity constructor.
     *
     * @param array          $tokens
     * @param Tokenizer|null $tokenizer
     * @param array          $additionalData
     */
    public function __construct($tokens, &$tokenizer = null, $additionalData = [])
    {
        $this->parse($tokens, $tokenizer, $additionalData);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function stringify()
    {
        $result = '';
        foreach ($this->contents as $content) {
            $result .= $content->stringify();
        }

        return $result;
    }

    public function addContents($contents)
    {
        foreach ($contents as $content) {
            $this->addContent($content);
        }
    }

    public function addContent($content)
    {
        $data = $this->processContent($content);
        if ($data instanceof self) {
            $this->contents[] = $content;
        } else {
            $this->contents[] = new TokenEntity([$content]);
        }
    }

    public function findLastPosition($type = null)
    {
        $count = count($this->contents);

        if (!$this->endWithBracket && empty($type)) {
            return $count > 0 ? $count - 1 : 0;
        }

        for ($i = $count - 1; $i > 0; $i--) {
            if (!empty($type)) {
                if ($this->contents[$i]->getType() === $type) {
                    return $i;
                }
            } else {
                if ($this->contents[$i]->getType() === 'Token' && $this->contents[$i]->stringify() === '}') {
                    return $i - 1;
                }
            }
        }

        if ($this->endWithBracket && empty($type)) {
            return $count > 0 ? $count - 1 : 0;
        }

        return $this->findLastPosition();
    }

    protected function processContent($data)
    {
        return $data;
    }

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
        $tokens    = [];
        $modifiers = [];
        $depth     = 0;
        while (!$tokenizer->finished()) {
            $token = $tokenizer->getNextToken();
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_ABSTRACT:
                    case T_PUBLIC:
                    case T_PRIVATE:
                    case T_STATIC:
                    case T_PROTECTED:
                        if (count($modifiers) === 0) {
                            $this->addContents($tokens);
                            $tokens = [];
                        }
                        $tokens[]    = $token;
                        $modifiers[] = $token[1];
                        break;
                    case T_NAMESPACE:
                        $this->addContents($tokens);
                        $entity = new NamespaceEntity(array_merge($tokens, [$token]), $tokenizer);
                        $this->addContent($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
                    case T_INTERFACE:
                        $entity = new InterfaceEntry(
                            array_merge($tokens, [$token]),
                            $tokenizer,
                            ['modifiers' => $modifiers]
                        );
                        $this->addContents($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
                    case T_CLASS:
                        $entity = new ClassEntity(
                            array_merge($tokens, [$token]),
                            $tokenizer,
                            ['modifiers' => $modifiers]
                        );
                        $this->addContent($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
                    case T_TRAIT:
                        $entity = new TraitEntry(
                            array_merge($tokens, [$token]),
                            $tokenizer,
                            ['modifiers' => $modifiers]
                        );
                        $this->addContent($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
                    case T_FUNCTION:
                        $entity = new FunctionEntity(
                            array_merge($tokens, [$token]),
                            $tokenizer,
                            ['modifiers' => $modifiers]
                        );
                        $this->addContent($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
                    case T_USE:
                        $this->addContents($tokens);
                        $entity = new UseEntity(array_merge($tokens, [$token]), $tokenizer);
                        $this->addContent($entity);
                        $tokens    = [];
                        $modifiers = [];
                        break;
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
                    case ';':
                        $modifiers = [];
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

    public function getContent()
    {
        return $this->contents;
    }
}
