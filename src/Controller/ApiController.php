<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Student;
use App\Form\StudentFormType;
use App\Project\ProjectManager;
use App\Student\StudentManager;
use Doctrine\DBAL\Exception as DbalException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var StudentManager
     */
    private $studentManager;

    public function __construct(StudentManager $studentManager, ProjectManager $projectManager)
    {
        $this->studentManager = $studentManager;
    }

    /**
     * @Route("/api/student/{id}", name="api_create_student", methods={"POST"})
     */
    public function createStudent(Request $request, Project $project): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentFormType::class, $student);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            try {
                $this->studentManager->createStudent($student, $project);
            } catch (DbalException $exception) {
                // todo add logging to file
                return new JsonResponse($this->createResponse($student, 'Database error'), 500);
            } catch (\Exception $exception) {
                return new JsonResponse($this->createResponse($student, $exception->getMessage()), 500);
            }

            return new JsonResponse($this->createResponse($student));
        }

        return new JsonResponse($this->createResponse($student, 'Something went wrong', 500));
    }

    private function createResponse(Student $student, ?string $error = null): array
    {
        return [
            'id' => $student->getId(),
            'error' => $error,
        ];
    }
}
