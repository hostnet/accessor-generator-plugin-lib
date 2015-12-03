<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Student;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class StudentTest extends \PHPUnit_Framework_TestCase
{
    public function testSetStudent()
    {
        $stefan = new Student();
        $nico   = new Student();

        $nico->setStudent($stefan);
        $this->assertSame($stefan, $nico->getStudent());

        $stefan->setStudent($nico);
        $this->assertSame($nico, $stefan->getStudent());
        $this->assertSame($stefan, $nico->getStudent());
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testGetStudentEmpty()
    {
        $nico = new Student();
        $nico->getStudent();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetStudentTooManyArguments()
    {
        $nico = new Student();
        $nico->getStudent(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetStudentTooManyArguments()
    {
        $stefan = new Student();
        $nico   = new Student();
        $nico->setStudent($stefan, 2);
    }
}
