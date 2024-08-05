<?php

namespace Tests;

use Core\Session;
use Mockery;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Core\Database;
use Forms\TicketsForm;
use Mockery\MockInterface;

class DisplayFormTest extends TestCase
{
    protected MockInterface $dbMock;
    protected MockInterface $pdoMock;
    protected MockInterface $statementMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbMock = Mockery::mock(Database::class);
        $this->pdoMock = Mockery::mock(PDO::class);
        $this->statementMock = Mockery::mock(PDOStatement::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testStoresTicketData()
    {
        $this->dbMock->shouldReceive('query')->andReturnSelf();
        $this->dbMock->shouldReceive('find')->andReturn(['count' => 1]);

        $this->dbMock->shouldReceive('connection')->andReturn($this->pdoMock);
        $this->pdoMock->shouldReceive('beginTransaction');
        $this->dbMock->shouldReceive('query')->andReturnSelf();
        $this->dbMock->shouldReceive('lastId')->andReturn(2);
        $this->pdoMock->shouldReceive('prepare')->andReturn($this->statementMock);
        $this->pdoMock->shouldReceive('commit');
        $this->statementMock->shouldReceive('execute')->andReturnTrue();

        $_POST['name'] = 'John Doe';
        $_POST['email'] = 'john.doe@example.com';
        $_POST['phone'] = '1234567890';
        $_POST['tickets'] = [
            ['type_id' => 1, 'quantity' => 2],
            ['type_id' => 2, 'quantity' => 1],
        ];

        $form = new TicketsForm($this->dbMock);

        $invalid = $form->notValid();
        $this->assertFalse($invalid);

        $data = $form->getData();

        $this->assertEmpty(Session::all('errors'));

        $this->assertEquals('John Doe', $data['name']);
        $this->assertEquals('john.doe@example.com', $data['email']);
        $this->assertEquals('1234567890', $data['phone']);
        $this->assertEquals([
            ['type_id' => 1, 'quantity' => 2],
            ['type_id' => 2, 'quantity' => 1],
        ], $data['tickets']);
    }
    public function testStoresTicketValidation()
    {
        $this->dbMock->shouldReceive('query')->andReturnSelf();
        $this->dbMock->shouldReceive('find')->andReturn(['count' => 0])->once();
        $this->dbMock->shouldReceive('find')->andReturn(['count' => 1]);

        $_POST['name'] = '';
        $_POST['email'] = 'john.doeATexample.com';
        $_POST['phone'] = '12345';
        $_POST['tickets'] = [
            ['type_id' => 3, 'quantity' => 2],
            ['type_id' => 2, 'quantity' => 0],
        ];

        $form = new TicketsForm($this->dbMock);

        $invalid = $form->notValid();
        $this->assertTrue($invalid);

        $this->assertEquals([
            'name'=> 'Name is required',
            'phone' => 'Phone number must be at least 9 digits long',
            'email' => 'Email must be a valid email address',
            'tickets[0][type_id]' => 'Ticket type does not exist',
            'tickets[1][quantity]' => 'Quantity must be at least 1'
        ], $form->errors());
    }
}
