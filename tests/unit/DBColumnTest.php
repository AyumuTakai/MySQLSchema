<?php

class DBColumnTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }
    public function testFromSQL()
    {
        $column = DBColumn::fromSQL('');
        $this->assertNull($column);

        $column = DBColumn::fromSQL('id int PRIMARY KEY');
        $this->assertInstanceOf('DBColumn',$column);
        $this->assertEquals('id',$column->name);
        $this->assertEquals('int',$column->type);
        $this->assertTrue($column->primaryKey);

        $column = DBColumn::fromSQL('name varchar(20)');
        $this->assertEquals('name',$column->name);
        $this->assertEquals('varchar',$column->type);
        $this->assertEquals(20,$column->length);

        $column = DBColumn::fromSQL('code char(10)');
        $this->assertEquals('code',$column->name);
        $this->assertEquals('char',$column->type);
        $this->assertEquals(10,$column->length);
    }
    public function testCompare(){

        $column1 = DBColumn::fromSQL('id int');
        $column2 = DBColumn::fromSQL('id int');
        $column3 = DBColumn::fromSQL('num int');

        $this->assertEquals($column1,$column2);
        $this->assertNotEquals($column1,$column3);

        $column4 = DBColumn::fromSQL('name varchar(20)');
        $column5 = DBColumn::fromSQL('name varchar(10)');
        $this->assertNotEquals($column4,$column5);

    }
    public function testToString(){
        $column1 = DBColumn::fromSQL('id int');
        $this->assertEquals('id int',(string)$column1);
        $this->assertEquals('id int',$column1->toSQL());

        $column2 = DBColumn::fromSQL('name varchar(20)');
        $this->assertEquals('name varchar(20)',(string)$column2);
        $this->assertEquals('name varchar(20)',$column2->toSQL());
    }
    /**
     */
    public function testDiff() {
        $column1 = DBColumn::fromSQL('id int');
        $column2 = DBColumn::fromSQL('id int');
        $column3 = DBColumn::fromSQL('num int');
        $column4 = DBColumn::fromSQL('name varchar(20)');

        $diff1 = $column1->diff($column2);
        $this->assertEquals('',$diff1);

        $diff2 = $column1->diff($column3);
        $this->assertEquals('CHANGE id num int',$diff2);

        $diff3 = $column1->diff($column4);
        $this->assertEquals('CHANGE id name varchar(20)',$diff3);
    }
}
