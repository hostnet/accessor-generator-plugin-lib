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
     * Get student
     *
     * @return StudentInterface
     * @throws \InvalidArgumentException
     */
    public function getStudent()
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
     * Set student
     *
     * @param StudentInterface $student
     * @return Student
     * @throws \BadMethodCallException if the number of arguments is not correct
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
