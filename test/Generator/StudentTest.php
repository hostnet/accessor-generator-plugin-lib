<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Student;
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

    public function testGetStudentEmpty(): void
    {
        $nico = new Student();

        $this->expectException(EntityNotFoundException::class);

        $nico->getStudent();
    }

    public function testGetStudentTooManyArguments(): void
    {
        $nico = new Student();

        $this->expectException(\BadMethodCallException::class);

        $nico->getStudent(1);
    }

    public function testSetStudentTooManyArguments(): void
    {
        $stefan = new Student();
        $nico   = new Student();

        $this->expectException(\BadMethodCallException::class);

        $nico->setStudent($stefan, 2);
    }
}
