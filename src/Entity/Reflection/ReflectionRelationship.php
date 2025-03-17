<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\joins\JoinColumn;
use Seal\LaravelDataMapper\Attributes\Relationships\ManyToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\ManyToOne;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToOne;
use Seal\LaravelDataMapper\Exceptions\EntityException;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class ReflectionRelationship extends ReflectionNode
{
    public const ONE_TO_ONE = 'ONE_TO_ONE';
    public const ONE_TO_MANY = 'ONE_TO_MANY';
    public const MANY_TO_ONE = 'MANY_TO_ONE';
    public const MANY_TO_MANY = 'MANY_TO_MANY';

    private ReflectionProperty $property;
    private readonly string $name;
    private readonly string $entityClassName;
    private readonly string $relationshipType;
    private readonly string $columnName;
    private readonly string $referencedColumnName;
    private readonly string $joinType;

    public  function __construct(ReflectionProperty $property) {
        $this->property = $property;
        parent::__construct();
    }

    public function initialize()
    {
        $this->name = $this->property->getName();
        $this->entityClassName = $this->property->getType();
        $this->detectType();
        $this->setReferences();
    }

    /**
     * @throws EntityException
     */
    private function setReferences()
    {
        $joinColumnAttribute = ReflectionUtil::getPropAttribute($this->property, JoinColumn::class);

        if (!$joinColumnAttribute) {
            throw new EntityException("The join has no any attributes");
        }

        $args = $joinColumnAttribute->getArguments();
        $this->columnName = $args['columnName'] ?? '';
        $this->referencedColumnName = $args['referencedColumnName'] ?? '';
        $this->joinType = $args['joinType']->value ?? 'JOIN';
    }

    private function detectType()
    {
        $mapping = [
            OneToOne::class => self::ONE_TO_ONE,
            OneToMany::class => self::ONE_TO_MANY,
            ManyToOne::class => self::MANY_TO_ONE,
            ManyToMany::class => self::MANY_TO_MANY,
        ];

        foreach ($this->property->getAttributes() as $attribute) {
            if (isset($mapping[$attribute->getName()])) {
                $this->relationshipType = $mapping[$attribute->getName()];
                break;
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRelationshipType(): string
    {
        return $this->relationshipType;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getReferencedColumnName(): string
    {
        return $this->referencedColumnName;
    }

    /**
     * @return string
     */
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    /**
     * @return string
     */
    public function getJoinType(): string
    {
        return $this->joinType;
    }
}