<?php
namespace TakaakiMizuno\PhpCodeManipulator\Tests;

use TakaakiMizuno\PhpCodeManipulator\Parser;

class ParserTest extends Base
{
    public function testParseClass()
    {
        $parser = new Parser();
        $this->assertNotEmpty($parser);

        $entry = $parser->parse('src/Entities/BaseEntity.php');

        $this->assertEquals('File', $entry->getType());

        $namespace = $entry->getNamespace();

        $this->assertNotEmpty($namespace);
        $this->assertEquals('Namespace', $namespace->getType());

        $classes = $entry->getClasses();
        foreach ($classes as $class) {
            print PHP_EOL.PHP_EOL.$class->getName().PHP_EOL;
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                print  '>'.$method->getName().PHP_EOL;
            }
        }
    }

    public function testAddMethods()
    {
        $parser = new Parser();
        $this->assertNotEmpty($parser);

        $entry = $parser->parse('src/Entities/BaseEntity.php');

        $this->assertEquals('File', $entry->getType());

        $namespace = $entry->getNamespace();

        $this->assertNotEmpty($namespace);
        $this->assertEquals('Namespace', $namespace->getType());

        $classes = $entry->getClasses();
        foreach ($classes as $class) {
            print PHP_EOL.PHP_EOL.$class->getName().PHP_EOL;
            $class->addMethod('/** **/', 'newMethod', [
                '$test1',
                '$test2',
            ], ['public'], '$a=1');
            print $class->stringify();
        }
    }
}
