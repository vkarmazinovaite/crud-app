<?php

namespace App\Student;

use App\Entity\Project;
use App\Entity\Student;
use App\Repository\ProjectRepository;
use App\Repository\StudentRepository;

class StudentManager
{
    /**
     * @var StudentRepository
     */
    private $studentRepo;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepo = $studentRepo;
    }

    /**
     * @param Student $student
     * @param Project $project
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     */
    public function createStudent(Student $student, Project $project): void
    {
        $student->setProject($project);

        //todo: catch unique student error
        $this->studentRepo->add($student);
    }

    public function deleteStudent(int $id): void
    {
        //todo: if no student, catch and redirect with message
        $student = $this->studentRepo->findOneBy(['id' => $id]);

        if (!$student) {
           throw new \InvalidArgumentException('Student not found');
        }

        $this->studentRepo->remove($student);
    }

    public function getStudent(int $id): ?Student
    {
        return $this->studentRepo->findOneBy(['id' => $id]);
    }
}
