<?php

namespace Seal\LaravelDataMapper\Entity;

use Illuminate\Support\Facades\DB;
use ReflectionException;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;

class Matcher
{
    private array $schema;
    private ReflectionEntity $reflectionEntity;


    /**
     * @throws ReflectionException
     */
    public function __construct(string $entityClass)
    {
        $this->reflectionEntity = new ReflectionEntity($entityClass);
        $this->schema = $this->getSchema();

//        foreach ($reflectionEntity->getColumns() as $column) {
//            $this->getTableColumnProperties();
//        }
    }

    public function matches()
    {

    }

    public function getTableColumnProperties(string $columnName) {}

    public function getSchema(): array
    {
        $schema = [];
        $columns = DB::select("DESCRIBE " . $this->tableName);
        foreach ($columns as $column) {
            $schema[$column->Field] = [
                'type' => $column->Type,
                'nullable' => $column->Null === 'YES',
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra
            ];
        }

        return $schema;
    }
}