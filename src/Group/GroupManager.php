<?php

namespace App\Group;

use App\Entity\Group;
use App\Entity\Student;
use App\Repository\GroupRepository;

class GroupManager
{
    /**
     * @var GroupRepository
     */
    private $groupRepo;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    public function addStudentToGroup(Group $group, Student $student)
    {
        $group->addStudent($student);
        $this->groupRepo->add($group);
    }

    public function removeStudentFromGroup(Student $student)
    {
        $group = $student->getStudentGroup();
        $group->removeStudent($student);
        $this->groupRepo->add($group);
    }

    public function getGroup(int $id): ?Group
    {
        return $this->groupRepo->findOneBy(['id' => $id]);
    }
}
