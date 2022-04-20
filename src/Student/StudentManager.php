<?php

namespace App\Student;

use App\Entity\Project;
use App\Entity\Student;
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

        $this->studentRepo->add($student);
    }

    public function deleteStudent(int $id): void
    {
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

    public function getUnassignedStudents(Project $project): array
    {
        return $this->studentRepo->findUnassignedStudents($project->getId());
    }
}
