<?php

/**
 * Author: Xooxx <xooxx.dev@gmail.com>
 * Date: 7/26/15
 * Time: 12:44 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Xooxx\Api\Mapping;

class Mapper
{
    /** @var array */
    protected $classMap = [];
    /** @var array */
    protected $aliasMap = [];

    /**
     * @param array $mappings
     *
     * @throws MappingException
     * @throws \ReflectionException
     */
    public function __construct(array $mappings = null)
    {
        if (\is_array($mappings)) {
            foreach ($mappings as $mappedClass) {
                $mapping = $this->buildMapping($mappedClass);
                $this->classMap[ltrim($mapping->getClassName(), '\\')] = $mapping;
                $this->aliasMap[ltrim($mapping->getClassAlias(), '\\')][] = $mapping->getClassName();
            }
        }
    }

    /**
     * @param string|array $mappedClass
     *
     * @return Mapping
     * @throws \ReflectionException
     */
    protected function buildMapping($mappedClass)
    {
        return \is_string($mappedClass) && \class_exists($mappedClass, true) ? MappingFactory::fromClass($mappedClass) : MappingFactory::fromArray($mappedClass);
    }
    /**
     * @return array
     */
    public function getClassMap()
    {
        return (array) $this->classMap;
    }
    /**
     * @param array $array
     */
    public function setClassMap(array $array)
    {
        $this->classMap = $array;
    }
}