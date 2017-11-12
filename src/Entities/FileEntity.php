<?php
namespace TakaakiMizuno\PhpCodeManipulator\Entities;

class FileEntity extends BaseEntity
{
    /** @var string $type */
    protected $type = 'File';

    /** @var null|NamespaceEntity $namespace */
    protected $namespace = null;

    /** @var ClassEntity[] */
    protected $classes    = [];

    /** @var TraitEntry[] */
    protected $traits     = [];

    /** @var InterfaceEntry[] */
    protected $interfaces = [];

    /** @var FunctionEntity[] */
    protected $functions = [];

    /**
     * @return null|NamespaceEntity
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    protected function processContent($data)
    {
        if ($data instanceof ClassEntity) {
            $this->classes[] = $data;
        } elseif ($data instanceof TraitEntry) {
            $this->traits[] = $data;
        } elseif ($data instanceof InterfaceEntry) {
            $this->interfaces[] = $data;
        } elseif ($data instanceof FunctionEntity) {
            $this->functions[] = $data;
        } elseif ($data instanceof NamespaceEntity) {
            $this->namespace = $data;
        }

        return $data;
    }

    /**
     * @return ClassEntity[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return InterfaceEntry[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @return TraitEntry[]
     */
    public function getTraits()
    {
        return $this->traits;
    }

    /**
     * @return FunctionEntity[]
     */
    public function getFunctions()
    {
        return $this->functions;
    }
}
