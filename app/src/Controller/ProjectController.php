<?php

namespace App\Controller;
use App\Entity\Project;
use App\Repository\DeveloperRepository;
use App\Entity\DeveloperProject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'project_new_create', methods: ['POST'])]
    public function createProject(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $Project = new Project();
        $Project->setClient($data['clientName']);
        $Project->setNameProject($data['projectName']);
        // Проверка валидации
        $errors = $validator->validate($Project);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        // Сохранение нового пользователя
        $entityManager->persist($Project);
        $entityManager->flush();

        return new Response('Проект успешно создан!', Response::HTTP_CREATED);
    }

    #[Route('/project/{id}', name: 'app_project_delete', methods: ['DELETE'])]
    public function deleteProject(int $id, EntityManagerInterface $entityManager): Response
    {
        $Project = $entityManager->getRepository(Project::class)->find($id);

        if (!$Project) {
            return new Response('проект не найден', Response::HTTP_NOT_FOUND);
        }

        $resultsFind = $entityManager->getRepository(DeveloperProject::class)->findBy(["project_id"=>$id]);
        foreach ($resultsFind as $result) {
            $entityManager->remove($result);
        }

        $entityManager->remove($Project);
        $entityManager->flush();

        return new Response('Проект успешно удалён', Response::HTTP_OK);
    }
    /**
    * updateProject - Обновление информации о проекте
    *
    * Данная функция обновляет информацию по его идентификатору
    *
    */

    #[Route('/project/{id}', name: 'app_project_update', methods: ['PUT'])]
    public function updateProject(int $id, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return new Response('Проект не найден', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $project->setNameProject($data['projectName'] ?? $project->getNameProject());
        $project->setClient($data['clientName'] ?? $project->getClient());

        // Проверка валидации
        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        // Обновление разработчика
        $entityManager->flush();

        return new Response('Информация о проекте обновлена', Response::HTTP_OK);
    }
}
