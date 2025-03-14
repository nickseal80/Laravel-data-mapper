<?php
namespace Tests\DataMapping\Internal;

use App\Entities\User;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\DatabaseManager;
use Seal\LaravelDataMapper\DataMapping\Internal\DataMapper;
use Seal\LaravelDataMapper\Hydrator\Hydrator;
use ReflectionException;

class DataMapperTest extends TestCase
{
    protected DatabaseManager|MockObject $db;
    protected Connection|MockObject $connection;
    protected MockObject|Hydrator $hydrator;
    protected DataMapper $dataMapper;


    protected function setUp(): void
    {
        // Создаем мок для DatabaseManager
        $this->db = $this->getMockBuilder(DatabaseManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['connection'])  // Мокаем только метод connection
            ->getMock();

        // Мокаем Connection
        $connection = $this->getMockBuilder(\Illuminate\Database\Connection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['table']) // Мокаем метод table
            ->getMock();

        // Мокаем QueryBuilder
        $queryBuilder = $this->getMockBuilder(\Illuminate\Database\Query\Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->getMock();

        // Мокаем возвращаемое значение для connection
        $this->db->method('connection')->willReturn($connection);

        // Мокаем метод table у Connection, чтобы вернуть QueryBuilder
        $connection->method('table')->with('users')->willReturn($queryBuilder);

        // Мокаем метод where на QueryBuilder
        $queryBuilder->method('where')->with('id', 1)->willReturnSelf();

        // Мокаем метод first на QueryBuilder
        $queryBuilder->method('first')->willReturn((object) ['id' => 1, 'name' => 'Test']);

        // Создаем мок для гидратора
        $this->hydrator = $this->createMock(Hydrator::class);

        // Инициализируем DataMapper
        $this->dataMapper = new class($this->db, $this->hydrator) extends DataMapper {};
    }

    public function testForEntity()
    {
        $entityClass = User::class;
        $clonedDataMapper = $this->dataMapper->forEntity($entityClass);

        $this->assertNotSame($this->dataMapper, $clonedDataMapper);
        $this->assertInstanceOf(DataMapper::class, $clonedDataMapper);
    }

    public function testSetEntityClass()
    {
        $entityClass = User::class;
        $this->dataMapper->setEntityClass($entityClass);

        $this->assertSame($entityClass, $this->getProperty($this->dataMapper, 'entityClass'));
    }

    private function getProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);

        return $property->getValue($object);
    }
}