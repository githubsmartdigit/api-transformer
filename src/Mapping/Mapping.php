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

use Xooxx\Api\Transformer\Helpers\RecursiveFormatterHelper;
class Mapping
{
    use NullableTrait;
    /** @var string */
    protected $className;
    /** @var string */
    protected $resourceUrlPattern;
    /** @var string */
    protected $classAlias;
    /** @var array */
    protected $aliasedProperties;
    /** @var array */
    protected $hiddenProperties;
    /** @var array */
    protected $requiredProperties;
    /** @var array */
    protected $idProperties;
    /** @var array */
    protected $relationships;
    /** @var array */
    protected $metaData;
    /** @var string */
    protected $selfUrl;
    /** @var array */
    protected $otherUrls;
    /** @var array */
    protected $relationshipSelfUrl;
    /** @var array */
    protected $filterKeys;
    /** @var array */
    protected $curies;
    /** @var array */
    protected $properties;
    /** @var array */
    protected $includedKeys;
    /** @var bool */
    protected $filteringIncluded;
    /**
     * Mapping constructor.
     *
     * @param string      $className
     * @param string|null $resourceUrlPattern
     * @param array       $idProperties
     */
    public function __construct($className, $resourceUrlPattern = null, array $idProperties = [])
    {
        $this->className = $className;
        $this->resourceUrlPattern = $resourceUrlPattern;
        $this->idProperties = $idProperties;
    }
    /**
     * @return string
     */
    public function getClassAlias()
    {
        return (string) $this->classAlias;
    }
    /**
     * @param string $aliasedClass
     *
     * @return $this
     */
    public function setClassAlias($aliasedClass)
    {
        $this->classAlias = RecursiveFormatterHelper::camelCaseToUnderscore(RecursiveFormatterHelper::namespaceAsArrayKey($aliasedClass));
        return $this;
    }
    /**
     * @return array
     */
    public function getIdProperties()
    {
        return (array) $this->idProperties;
    }
    /**
     * @param string $idProperty
     */
    public function addIdProperty($idProperty)
    {
        $this->idProperties[] = $idProperty;
    }
    /**
     * @param string $propertyName
     */
    public function hideProperty($propertyName)
    {
        $this->hiddenProperties[] = $propertyName;
    }
    /**
     * @param string $propertyName
     */
    public function requireProperty($propertyName)
    {
        $this->requiredProperties[] = $propertyName;
    }
    /**
     * @param string $propertyName
     * @param string $propertyAlias
     */
    public function addPropertyAlias($propertyName, $propertyAlias)
    {
        $this->aliasedProperties[$propertyName] = $propertyAlias;
        $this->updatePropertyMappings($propertyName, $propertyAlias);
    }
    /**
     * @param string $propertyName
     * @param string $propertyAlias
     */
    protected function updatePropertyMappings($propertyName, $propertyAlias)
    {
        if (\in_array($propertyName, (array) $this->idProperties)) {
            $position = \array_search($propertyName, $this->idProperties, true);
            $this->idProperties[$position] = $propertyAlias;
        }
        $search = \sprintf('{%s}', $propertyName);
        $replace = \sprintf('{%s}', $propertyAlias);
        $this->selfUrl = \str_replace($search, $replace, $this->selfUrl);
        $this->resourceUrlPattern = \str_replace($search, $replace, $this->resourceUrlPattern);
        $this->otherUrls = \str_replace($search, $replace, $this->otherUrls);
    }
    /**
     * @param array $properties
     */
    public function setPropertyNameAliases(array $properties)
    {
        $this->aliasedProperties = \array_merge((array) $this->aliasedProperties, $properties);
        foreach ($this->aliasedProperties as $propertyName => $propertyAlias) {
            $this->updatePropertyMappings($propertyName, $propertyAlias);
        }
    }
    /**
     * @return array
     */
    public function getProperties()
    {
        return (array) $this->properties;
    }
    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }
    /**
     * @return string
     */
    public function getClassName()
    {
        return (string) $this->className;
    }
    /**
     * @return string
     */
    public function getResourceUrl()
    {
        return (string) $this->resourceUrlPattern;
    }
    /**
     * @return array
     */
    public function getAliasedProperties()
    {
        return (array) $this->aliasedProperties;
    }
    /**
     * @return array
     */
    public function getHiddenProperties()
    {
        return (array) $this->hiddenProperties;
    }
    /**
     * @return array
     */
    public function getRequiredProperties()
    {
        return (array) $this->requiredProperties;
    }
    /**
     * @param array $hidden
     */
    public function setHiddenProperties(array $hidden)
    {
        $this->hiddenProperties = \array_merge((array) $this->hiddenProperties, $hidden);
    }
    /**
     * @param array $required
     */
    public function setRequiredProperties(array $required)
    {
        $this->requiredProperties = \array_merge((array) $this->requiredProperties, $required);
    }
    /**
     * @return array
     */
    public function getRelationships()
    {
        return (array) $this->relationships;
    }
    /**
     * @param array $relationships
     */
    public function addAdditionalRelationships(array $relationships)
    {
        $this->relationships = $relationships;
    }
    /**
     * @return array
     */
    public function getMetaData()
    {
        return (array) $this->metaData;
    }
    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData)
    {
        $this->metaData = $metaData;
    }
    /**
     * @param string $key
     * @param $value
     */
    public function addMetaData($key, $value)
    {
        $this->metaData[$key] = $value;
    }
    /**
     * @return string
     */
    public function getSelfUrl()
    {
        return (string) $this->selfUrl;
    }
    /**
     * @param string $self
     */
    public function setSelfUrl($self)
    {
        $this->selfUrl = $self;
    }
    /**
     * @param string $propertyName
     *
     * @return string
     */
    public function getRelatedUrl($propertyName)
    {
        return !empty($this->relationshipSelfUrl[$propertyName]['related']) ? $this->relationshipSelfUrl[$propertyName]['related'] : '';
    }
    /**
     * @return array
     */
    public function getFilterKeys()
    {
        return (array) $this->filterKeys;
    }
    /**
     * @param array $filterKeys
     */
    public function setFilterKeys(array $filterKeys)
    {
        $this->filterKeys = $filterKeys;
    }
    /**
     * @param string $propertyName
     * @param $urls
     *
     * @return $this
     */
    public function setRelationshipUrls($propertyName, $urls)
    {
        $this->relationshipSelfUrl[$propertyName] = $urls;
        return $this;
    }
    /**
     * @param $propertyName
     *
     * @return string
     */
    public function getRelationshipSelfUrl($propertyName)
    {
        return !empty($this->relationshipSelfUrl[$propertyName]['self']) ? $this->relationshipSelfUrl[$propertyName]['self'] : '';
    }
    /**
     * @param array $urls
     */
    public function setUrls(array $urls)
    {
        $this->otherUrls = $urls;
    }
    /**
     * @return array
     */
    public function getUrls()
    {
        return (array) $this->otherUrls;
    }
    /**
     * @return array
     */
    public function getCuries()
    {
        return (array) $this->curies;
    }
    /**
     * @param array $curies
     *
     * @throws MappingException
     */
    public function setCuries(array $curies)
    {
        if (empty($curies['name']) || empty($curies['href'])) {
            throw new MappingException('Curies must define "name" and "href" properties');
        }
        $this->curies = $curies;
    }
    /**
     * Used by JSON API included resource filtering.
     *
     * @param $resource
     */
    public function addIncludedResource($resource)
    {
        $this->includedKeys[] = $resource;
    }
    /**
     * Returns the allowed included resources.
     *
     * @return array
     */
    public function getIncludedResources()
    {
        return (array) $this->includedKeys;
    }
    /**
     * @param bool $filtering
     */
    public function filteringIncludedResources($filtering = true)
    {
        $this->filteringIncluded = $filtering;
    }
    /**
     * Returns true if included resource filtering has been set, false otherwise.
     *
     * @return bool
     */
    public function isFilteringIncludedResources()
    {
        return null === $this->filteringIncluded ? true : $this->filteringIncluded;
    }
}