<?php
// Generated at 2014-12-10 17:27:16 by hboomsma on se18-03-73-3f-9f-e0

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
            throw new \Doctrine\ORM\EntityNotFoundException(
                'Property "student" references an other entity ' .
                'but is not found and also is not nullable for parameter student.'
            );
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
