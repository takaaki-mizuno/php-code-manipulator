<?php
namespace TakaakiMizuno\PhpCodeManipulator;

class Tokenizer
{
    /** @var array */
    protected $tokens;

    /** @var int */
    protected $position;

    public function __construct($data)
    {
        $this->setTokens($data);
    }

    public static function fromFile($path)
    {
        $path = realpath($path);
        $data = file_get_contents($path);

        return new static($data);
    }

    public static function fromString($data)
    {
        return new static($data);
    }

    public function getNextToken()
    {
        if ($this->finished()) {
            return null;
        }

        $token = $this->tokens[$this->position];
        $this->position++;

        return $token;
    }

    /**
     * @return bool
     */
    public function finished()
    {
        return count($this->tokens) == $this->position;
    }

    public function getTokenContent($token)
    {
        return is_array($token) ? $token[1] : $token;
    }

    public function checkToken($token, $type)
    {
        if (is_array($token) && $token[0] === $type) {
            return true;
        }

        if (!is_array($token) && $token === $type) {
            return true;
        }

        return false;
    }

    public function getName()
    {
        if ($this->finished()) {
            return null;
        }

        $token = $this->getNextToken();
        while ($this->checkToken($token, T_WHITESPACE)) {
            $token = $this->getNextToken();
        }

        $name = '';
        while (!($this->checkToken($token, T_WHITESPACE) || $this->checkToken($token, '{') || $this->checkToken(
            $token,
                '}'
        ) || $this->checkToken($token, ';'))) {
            $name .= $this->getTokenContent($token);
            $token = $this->getNextToken();
        }

        return $name;
    }

    public function back()
    {
        if ($this->position > 0) {
            $this->position--;
        }
    }

    protected function setTokens($data)
    {
        $this->tokens   = token_get_all($data);
        $this->position = 0;
    }
}
