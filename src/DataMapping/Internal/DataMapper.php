<?php

namespace Seal\LaravelDataMapper\DataMapping\Internal;

use Illuminate\Database\DatabaseManager;
use ReflectionException;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapper implements DataMapperInterface
{
    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;

    public function __construct(DatabaseManager $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    /**
     * @throws ReflectionException
     */
    public function forEntity(string $entityClass): self
    {
        $clone = clone $this;
        $clone->setEntityClass($entityClass);
        return $clone;
    }

    /**
     * @throws ReflectionException
     */
    public function setEntityClass(string $entityClass)
    {
        $this->entityClass = $entityClass;
        $this->setTable();
    }

    /**
     * @throws ReflectionException
     */
    private function setTable()
    {
        $refEntity = new ReflectionEntity($this->entityClass);
        $this->table = $refEntity->takeTableName();
    }

    public function find(int $id)
    {
        $data = $this->db
            ->table($this->table)
            ->where('id', $id)
            ->first();

        if ($data) {
            return $this->hydrator->hydrate((array)$data, $this->entityClass);
        }

        return null;
    }

    public function save(object $entity): bool
    {
        $data = get_object_vars($entity);

        return $this->db->table($this->table)->updateOrInsert(['id' => $data['id']], $data);
    }

    public function delete(object $entity): int
    {
        return $this->db->table($this->table)->where('id', $entity->id)->delete();
    }
}
