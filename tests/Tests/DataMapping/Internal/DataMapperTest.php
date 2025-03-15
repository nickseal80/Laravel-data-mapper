<?php
namespace Tests\DataMapping\Internal;

use App\Entities\User;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\DatabaseManager;
use ReflectionException;
use Seal\LaravelDataMapper\DataMapping\Internal\DataMapper;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapperTest extends TestCase
{
    protected DatabaseManager|MockObject $db;
    protected Connection|MockObject $connection;
    protected Builder $queryBuilder;
    protected DataMapper $dataMapper;
    protected Hydrator $hydrator;


    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->db = $this->createMock(DatabaseManager::class);
        $this->connection = $this->createMock(Connection::class);
        $this->queryBuilder = $this->createMock(Builder::class);
        $this->hydrator = $this->createMock(Hydrator::class);
        $this->dataMapper = new class($this->db, $this->hydrator) extends DataMapper {};

        // 1️⃣ Говорим, что DatabaseManager::connection() вернет мок Connection
        $this->db->method('connection')->willReturn($this->connection);

        // 2️⃣ Говорим, что Connection::table() вернет мок QueryBuilder
        $this->connection->method('table')->willReturn($this->queryBuilder);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testFindById()
    {
        $data = [
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '$2y$12$8YlEy/ubT2TMbREWKVwTEermCMUjZ2vnlNYKNShuvZqoO.rWOKvey',
            'is_active' => 1,
            'remember_token' => Str::random(10),
        ];

        $this->queryBuilder->method('where')->with('id', 1)->willReturnSelf();
        $this->queryBuilder->method('first')->willReturn((object) $data);

        $entityClass = User::class;

        $returnableEntity = new $entityClass;
        $returnableEntity->setId($data['id']);
        $returnableEntity->setName($data['name']);
        $returnableEntity->setEmail($data['email']);
        $returnableEntity->setPassword($data['password']);
        $returnableEntity->setIsActive(true);
        $returnableEntity->setRememberToken($data['remember_token']);

        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->with($data, $entityClass)
            ->willReturn($returnableEntity);

        $user = $this->dataMapper->forEntity($entityClass)
            ->findById(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->getName());
        $this->assertEquals($data['email'], $user->getEmail());
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testForEntity()
    {
        $entityClass = User::class;
        $dataMapper = $this->dataMapper->forEntity($entityClass);

        $this->assertNotSame($this->dataMapper, $dataMapper);
        $this->assertInstanceOf(DataMapper::class, $dataMapper);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testSetEntityClass()
    {
        $entityClass = User::class;
        $this->dataMapper->setEntityClass($entityClass);

        $this->assertSame($entityClass, $this->getProperty($this->dataMapper, 'entityClass'));
    }

    /**
     * @throws ReflectionException
     */
    private function getProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);

        return $property->getValue($object);
    }
}