<?php

namespace App\Controller;

use App\Group\GroupManager;
use App\Student\StudentManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @var StudentManager
     */
    private $studentManager;
    /**
     * @var GroupManager
     */
    private $groupManager;

    public function __construct(StudentManager $studentManager, GroupManager $groupManager)
    {
        $this->studentManager = $studentManager;
        $this->groupManager = $groupManager;
    }

    /**
     * @Route("/group/add-student", name="group_add_student", methods={"POST"})
     */
    public function addStudentToGroup(Request $request, ManagerRegistry $doctrine): Response
    {
        $studentId = trim($request->request->get('student'));
        $groupId = trim($request->request->get('group'));

        $student = $this->studentManager->getStudent($studentId);
        $group = $this->groupManager->getGroup($groupId);

        if (!$student || !$group) {
            return new Response('Student not found', 404);
        }

        $projectId = $student->getProject()->getId();
        try {
            $this->groupManager->addStudentToGroup($group, $student);
        } catch (\Exception $exception) {
            //todo: flash message
        }

        return $this->redirectToRoute('view_project', ['id' => $projectId]);
    }

    /**
     * @Route("/group/remove-student/{studentId}", name="remove_from_group")
     */
    public function removeStudentFromGroup(Request $request, int $studentId): Response
    {
        $student = $this->studentManager->getStudent($studentId);

        if (!$student) {
            return new Response('Student not found', 404);
        }

        $projectId = $student->getProject()->getId();
        try {
            $this->groupManager->removeStudentFromGroup($student);
        } catch (\Exception $exception) {
            //todo: flash message
        }

        return $this->redirectToRoute('view_project', ['id' => $projectId]);
    }
}
