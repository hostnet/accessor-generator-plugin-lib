<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Student;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\StudentInterface;

trait StudentMethodsTrait
{
    /**
     * Gets student
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return StudentInterface
     */
    public function getStudent(): StudentInterface
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getStudent() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->student === null) {
            throw new \Doctrine\ORM\EntityNotFoundException('Missing required property "student".');
        }

        return $this->student;
    }

    /**
     * Sets student
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param StudentInterface $student
     *
     * @return $this|Student
     */
    public function setStudent(StudentInterface $student)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setStudent() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->student = $student;

        return $this;
    }
}
