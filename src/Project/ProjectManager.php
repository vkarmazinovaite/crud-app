<?php

namespace App\Project;

use App\Entity\Group;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\StudentRepository;

class ProjectManager
{
    /**
     * @var ProjectRepository
     */
    private $projectRepo;
    /**
     * @var StudentRepository
     */
    private $studentRepo;

    public function __construct(ProjectRepository $projectRepo, StudentRepository $studentRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->studentRepo = $studentRepo;
    }

    public function createProject(Project $project, int $groupCount): void
    {
        for($i = 1; $i <= $groupCount; $i++) {
            $group = new Group();
            $group->setTitle('Group #'. $i);
            $project->addGroup($group);
        }

        $this->projectRepo->add($project);
    }

    public function deleteProject(int $id): void
    {
        $project = $this->projectRepo->findOneBy(['id' => $id]);

        if ($project) {
            $this->projectRepo->remove($project);
        }
    }

    public function getProject(int $id): ?Project
    {
        return $this->projectRepo->findOneBy(['id' => $id]);
    }

    /**
     * @return Project[]
     */
    public function getAllProjects(): array
    {
        return $this->projectRepo->findAll();
    }

    public function getStudentGroups(int $projectId): array
    {
        $project = $this->projectRepo->findOneBy(['id' => $projectId]);
        $groups = $project->getGroups();

        $studentsPerGroup = $project->getStudentsPerGroup();
        $unassignedStudents = $this->studentRepo->findUnassignedStudents($projectId);
        $groupings = array();
        $i = 0;
        foreach ($groups as $group) {
            $groupStudents = $group->getStudents();
            $groupings[$i]['group'] = $group;
            $groupings[$i]['assigned'] = $groupStudents;
            $groupings[$i]['addMore'] = $studentsPerGroup - count($groupStudents);
            $i++;
        }

        return [$groupings, $unassignedStudents];
    }
}


