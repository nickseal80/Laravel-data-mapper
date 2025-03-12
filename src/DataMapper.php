<?php

namespace Seal\LaravelDataMapper;

use Illuminate\Database\DatabaseManager;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapper implements DataMapperInterface
{
    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;

    public function __construct(DatabaseManager $db, Hydrator $hydrator, string $table, string $entityClass)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->table = $table;
        $this->entityClass = $entityClass;
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
