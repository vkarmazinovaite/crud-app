<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Student;
use App\Form\ProjectFormType;
use App\Form\StudentFormType;
use App\Project\ProjectManager;
use App\Student\StudentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @var ProjectManager
     */
    private $projectManager;

    /**
     * @var StudentManager
     */
    private $studentManager;

    public function __construct(ProjectManager $projectManager, StudentManager $studentManager)
    {
        $this->projectManager = $projectManager;
        $this->studentManager = $studentManager;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $form = $this->createForm(ProjectFormType::class, null, [
            'action' => $this->generateUrl('create_project')
        ]);

        return $this->render('index.html.twig', [
            'projects' => $this->projectManager->getAllProjects(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/project/create", name="create_project", methods={"POST"})
     */
    public function createProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $groupCount = $form->get('groups')->getData();

            $this->projectManager->createProject($project, $groupCount);
            return $this->redirectToRoute('index');
        }

        return $this->renderForm('index.html.twig', [
            'projects' => $this->projectManager->getAllProjects(),
            'form' => $form,
        ]);
    }

    /**
     * @Route("/project/{id}", name="view_project")
     */
    public function viewProject(Project $project): Response
    {
        $student = new Student();
        $student->setProject($project);

        $form = $this->createForm(StudentFormType::class, $student, [
            'action' => $this->generateUrl('create_student', ['id' => $project->getId()])
        ]);

        $groupings = $this->projectManager->getStudentGroups($project);
        $unassignedStudents = $this->studentManager->getUnassignedStudents($project);

        return $this->render('project.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'groupings' => $groupings,
            'unassignedStudents' => $unassignedStudents
        ]);
    }

    /**
     * @Route("/project/{id}/delete", name="delete_project")
     */
    public function deleteProject(Project $project): Response
    {
        $this->projectManager->deleteProject($project);

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/student/create/{id}", name="create_student", methods={"POST"})
     */
    public function createStudent(Request $request, Project $project): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentFormType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            try {
                $this->studentManager->createStudent($student, $project);
            } catch (\Exception $exception) {
                $this->addFlash('warning', 'Something wrong! Student was not saved!');
            }
            return $this->redirectToRoute('view_project', ['id' => $student->getProject()->getId()]);
        }

        $groupings = $this->projectManager->getStudentGroups($project);
        $unassignedStudents = $this->studentManager->getUnassignedStudents($project);

        return $this->renderForm('project.html.twig', [
            'project' => $project,
            'form' => $form,
            'groupings' => $groupings,
            'unassignedStudents' => $unassignedStudents
        ]);
    }

    /**
     * @Route("/project/{projectId}/delete-student/{studentId}", name="project_delete_student")
     */
    public function deleteStudent(int $projectId, int $studentId): Response
    {
        $project = $this->projectManager->getProject($projectId);

        if (!$project) {
            return new Response('Project not found', 404);
        }

        try {
            $this->studentManager->deleteStudent($studentId);
        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Something wrong! Student was not deleted!');
        }

        return $this->redirectToRoute('view_project', ['id' => $project->getId()]);
    }
}
