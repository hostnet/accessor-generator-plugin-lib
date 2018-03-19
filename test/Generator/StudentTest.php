<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Student;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\StudentInterface;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function testSetStudent(): void
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
    public function testGetStudentEmpty(): void
    {
        $nico = new Student();
        $nico->getStudent();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetStudentTooManyArguments(): void
    {
        $nico = new Student();
        $nico->getStudent(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetStudentTooManyArguments(): void
    {
        $stefan = new Student();
        $nico   = new Student();
        $nico->setStudent($stefan, 2);
    }
}
