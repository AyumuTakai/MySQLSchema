<?php
require_once(__DIR__.'/../../DBTable.php');

class DBTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     */
    public function testFromSQL()
    {
        $table = DBTable::fromSQL('');
        $this->assertNull($table);

        $table = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $this->assertInstanceOf('DBTable',$table);
        $this->assertEquals('test',$table->name);
        $this->assertEquals(2,count($table->columns));
        $this->assertInstanceOf('DBColumn',$table->columns['id']);
        $this->assertInstanceOf('DBColumn',$table->columns['name']);
        $this->assertEquals('id',$table->columns['id']->name);
        $this->assertEquals('int',$table->columns['id']->type);
        $this->assertEquals('name',$table->columns['name']->name);
        $this->assertEquals('varchar',$table->columns['name']->type);
        $this->assertEquals('255',$table->columns['name']->length);
    }

    public function testCompare()
    {
        $table1 = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $table2 = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $table3 = DBTable::fromSQL('CREATE TABLE test (no int,name varchar(100))');
        $this->assertEquals($table1,$table2);
        $this->assertNotEquals($table1,$table3);
    }
    public function testToSQL()
    {
        $table1 = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $this->assertEquals('CREATE TABLE test (id int,name varchar(255))',(string)$table1);
        $this->assertEquals('CREATE TABLE test (id int,name varchar(255))',$table1->toSQL());
    }
    public function testDiff(){
        $table1 = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $table2 = DBTable::fromSQL('CREATE TABLE test (id int,name varchar(255))');
        $table3 = DBTable::fromSQL('CREATE TABLE test2 (id int,name varchar(255))');
        $table4 = DBTable::fromSQL('CREATE TABLE test3 (id int,name varchar(255))');
        $table5 = DBTable::fromSQL('CREATE TABLE test (id char(4),name varchar(255))');
        $table6 = DBTable::fromSQL('CREATE TABLE test (no int,name varchar(255))');

        $this->assertEquals('',$table1->diff($table2));
        $this->assertEquals('ALTER TABLE test RENAME test2',$table1->diff($table3));
        $this->assertEquals('ALTER TABLE test RENAME test3',$table1->diff($table4));
        $this->assertEquals('ALTER TABLE test CHANGE id id char(4)',$table1->diff($table5));
        $this->assertEquals('ALTER TABLE test ADD no int,DROP id',$table1->diff($table6));
    }
}