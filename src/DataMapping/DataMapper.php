<?php

namespace Seal\LaravelDataMapper\DataMapping;

use Illuminate\Database\DatabaseManager;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapper implements DataMapperInterface
{
    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;

    /**
     * @throws \ReflectionException
     */
    public function __construct(DatabaseManager $db, Hydrator $hydrator, string $entityClass)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->entityClass = $entityClass;
        $this->setTable();
    }

    /**
     * @throws \ReflectionException
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
