<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\Relationships\ManyToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToOne;

class ReflectionRelationship extends ReflectionNode
{
    public const ONE_TO_ONE = 'ONE_TO_ONE';
    public const ONE_TO_MANY = 'ONE_TO_MANY';
    public const MANY_TO_MANY = 'MANY_TO_MANY';

    private ReflectionProperty $property;
    private readonly string $name;
    private readonly string $relationshipType;
    public  function __construct(ReflectionProperty $property) {
        $this->property = $property;
        parent::__construct();
    }

    public function initialize()
    {
        $this->name = $this->property->getName();
        $this->takeType();
    }

    private function takeType()
    {
        $mapping = [
            OneToOne::class => self::ONE_TO_ONE,
            OneToMany::class => self::ONE_TO_MANY,
            ManyToMany::class => self::MANY_TO_MANY,
        ];

        foreach ($this->property->getAttributes() as $attribute) {
            if (isset($mapping[$attribute->getName()])) {
                $this->relationshipType = $mapping[$attribute->getName()];
                break;
            }
        }
    }

    private function getAttributes()
    {

    }
}