<?php

namespace App\Project;

use App\Entity\Group;
use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectManager
{
    /**
     * @var ProjectRepository
     */
    private $projectRepo;

    public function __construct(ProjectRepository $projectRepo)
    {
        $this->projectRepo = $projectRepo;
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

    public function deleteProject(Project $project): void
    {
        $this->projectRepo->remove($project);
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

    public function getStudentGroups(Project $project): array
    {
        $groups = $project->getGroups();
        $studentsPerGroup = $project->getStudentsPerGroup();

        $groupings = array();
        $i = 0;
        foreach ($groups as $group) {
            $groupStudents = $group->getStudents();
            $groupings[$i]['group'] = $group;
            $groupings[$i]['assigned'] = $groupStudents;
            $groupings[$i]['addMore'] = $studentsPerGroup - count($groupStudents);
            $i++;
        }

        return $groupings;
    }
}


