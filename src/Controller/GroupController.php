<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Student;
use App\Group\GroupManager;
use App\Student\StudentManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @Route("/group/{id}/add-student", name="group_add_student", methods={"POST"})
     */
    public function addStudentToGroup(Request $request, Group $group): Response
    {
        $studentId = trim($request->request->get('student'));
        $student = $this->studentManager->getStudent($studentId);

        if (!$student) {
            return new Response('Student not found', 404);
        }

        $projectId = $student->getProject()->getId();
        try {
            $this->groupManager->addStudentToGroup($group, $student);
        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Something wrong! Student was not added to group!');
        }

        return $this->redirectToRoute('view_project', ['id' => $projectId]);
    }

    /**
     * @Route("/group/remove-student/{id}", name="remove_from_group")
     */
    public function removeStudentFromGroup(Student $student): Response
    {
        $projectId = $student->getProject()->getId();
        try {
            $this->groupManager->removeStudentFromGroup($student);
        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Something wrong! Student was not removed from group!');
        }

        return $this->redirectToRoute('view_project', ['id' => $projectId]);
    }
}
