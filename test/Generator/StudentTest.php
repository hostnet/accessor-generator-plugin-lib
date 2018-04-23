<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Student;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\StudentInterface;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function testSetStudent()
    {
        $stefan = new Student();
        $nico   = new Student();

        $nico->setStudent($stefan);
        self::assertSame($stefan, $nico->getStudent());

        $stefan->setStudent($nico);
        self::assertSame($nico, $stefan->getStudent());
        self::assertSame($stefan, $nico->getStudent());
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
