<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

use TakaakiMizuno\PhpCodeManipulator\Tokenizer;
use TakaakiMizuno\PhpCodeManipulator\Utilities\ArrayHelper;

class ClassEntity extends ModifiableBaseEntity
{
    const INDENT = '    ';

    /** @var string $type */
    protected $type           = 'Class';

    protected $endWithBracket = true;

    /** @var FunctionEntity[] */
    protected $methods;

    /** @var PropertyEntity[] */
    protected $properties;

    /** @var ConstantEntity[] */
    protected $constants;

    /**
     * @return FunctionEntity[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return ConstantEntity[]
     */
    public function &getConstants()
    {
        return $this->constants;
    }

    /**
     * @return PropertyEntity[]
     */
    public function &getProperties()
    {
        return $this->properties;
    }

    public function hasMethod($name)
    {
        return !empty($this->getMethod($name));
    }

    public function &getMethod($name)
    {
        foreach ($this->methods as $method) {
            if ($method->getName() === $name) {
                return $method;
            }
        }

        return null;
    }

    public function hasProperty($name)
    {
        return !empty($this->getProperty($name));
    }

    public function &getProperty($name)
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        return null;
    }

    public function hasConstant($name)
    {
        return !empty($this->getConstant($name));
    }

    public function getConstant($name)
    {
        foreach ($this->constants as $constant) {
            if ($constant->getName() === $name) {
                return $constant;
            }
        }

        return null;
    }

    public function addMethod($phpdoc, $name, $arguments = [], $modifiers = ['public'], $content)
    {
        $index = $this->findLastPosition('Function');
        $text  = self::INDENT.$phpdoc.PHP_EOL.self::INDENT.implode(' ', $modifiers).' function '.$name.'('.implode(
            ' ',
                $arguments
        ).') {'.PHP_EOL.self::INDENT.self::INDENT.$content.PHP_EOL.self::INDENT.'}'.PHP_EOL;

        $tokenizer  = Tokenizer::fromString('<?php '.PHP_EOL.PHP_EOL.$text);
        $baseEntity = new BaseEntity(null, $tokenizer);

        $entities = $baseEntity->contents;
        ArrayHelper::delete($entities, 0, 1, true);

        foreach ($entities as $entity) {
            $index++;
            ArrayHelper::insert($this->contents, $entity, $index);
        }
    }

    protected function processContent($data)
    {
        if (empty($this->name) && is_array($data) && $data[0] === T_STRING) {
            $this->name = $data[1];
        }
        if ($data instanceof FunctionEntity) {
            $this->methods[] = $data;
        } elseif ($data instanceof PropertyEntity) {
            $this->properties[] = $data;
        } elseif ($data instanceof ConstantEntity) {
            $this->constants[] = $data;
        }

        return $data;
    }
}
