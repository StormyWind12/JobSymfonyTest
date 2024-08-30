<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Developer;
use App\Entity\DeveloperProject;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
class DeveloperProjectController extends AbstractController
{

    /**
    * linkUserProject - Добавляет проект новому пользователю
    *
    * Данная функция связывает пользователя и проект, добавляет в БД запись кто с чем связан 
    *
    */

    #[Route('/devproject', name: 'project_connect_developer', methods: ['POST'])]
    public function linkUserProject(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $link = new DeveloperProject();
        $link->setDeveloperId($data['developerid']);
        $link->setProjectId($data['projectid']);
        $errors = $validator->validate($link);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }
        $developerObject = $entityManager->getRepository(Developer::class)->findOneBy (["id"=>$link->getDeveloperId()]);
        $projectObject= $entityManager->getRepository(Project::class)->findOneBy (["id"=>$link->getProjectId()]);
        if (empty($developerObject)) {
            $errorsString = "Пользователь с таким ID не найден";
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }
        if (empty($projectObject)) {
            $errorsString = "Проект с таким ID не найден";
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($link);
        $entityManager->flush();
        return new Response('Проект успешно добавлен пользователю', Response::HTTP_CREATED);
    }

     /**
    * removeUserFromOneProject - Убирает определённый проект пользователю 
    *
    * Данная функция разрывает связь пользователя и проекта
    * убирает из БД запись о их связи 
    *
    */

    #[Route('/devproject/userremoveone', name: 'project_delete_developer_one', methods: ['DELETE'])]
    public function removeUserFromOneProject(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $resultsFind = $entityManager->getRepository(DeveloperProject::class)->findBy(["developer_id"=>$data['developerid'],"project_id"=>$data['projectid']]);
        if (empty($projectObject)) {
            $errorsString = "Данный сотрудник не нанят на данный проект";
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }
        foreach ($resultsFind as $result) {
            $entityManager->remove($result);
        }
        $entityManager->flush();

        return new Response('Пользователь успешно снят с проекта', Response::HTTP_CREATED);
    }

    /**
    * removeUserfromAllProjects - Убирает все проекты у пользователя 
    *
    * Данная функция разрывает связь пользователя и проектов с которыми он был связан
    * убирает из БД запись о их связи 
    *
    */

    #[Route('/devproject/userremoveall', name: 'project_delete_developer_all', methods: ['DELETE'])]
    public function removeUserfromAllProjects(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $resultsFind = $entityManager->getRepository(DeveloperProject::class)->findBy(["developer_id"=>$data['developerid']]);
        foreach ($resultsFind as $result) {
            $entityManager->remove($result);
        }
        $entityManager->flush();

        return new Response('Пользователь успешно снят со всех проектов', Response::HTTP_CREATED);

    }

     /**
    * projectRemoveAllUser - Убирает всех пользователей с проекта
    *
    * Данная функция разрывает связь пользователей и проекта с которыми они был связаны
    * убирает из БД запись о их связи 
    *
    */

    #[Route('/devproject/projectremoveall', name: 'project_delete_project_all', methods: ['DELETE'])]
    public function projectRemoveAllUser(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $resultsFind = $entityManager->getRepository(DeveloperProject::class)->findBy(["project_id"=>$data['projectid']]);
        foreach ($resultsFind as $result) {
            $entityManager->remove($result);
        }
        $entityManager->flush();

        return new Response('Все пользователи успешно сняты с проекта', Response::HTTP_CREATED);

    }
      /**
    * getStatisticdeveloper - получение статистики пользователей
    *
    * Функция отправляет всю статику о пользователях 
    * 
    *
    */
    #[Route('/devproject/statistic', name: 'app_devproject_statistic', methods: ['GET'])]
    public function getStatisticDevProject(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
            $projectsPerDeveloper = $entityManager->getRepository(DeveloperProject::class)->getProjectsPerDeveloper();
            $developersInProjects = $entityManager->getRepository(DeveloperProject::class)->getDevelopersInProjects();
            $averageAge = $entityManager->getRepository(Developer::class)->findAverageAge();
            $developerCountByPosition = $entityManager->getRepository(Developer::class)->getDeveloperCountByPosition();
            $developersByGender = $entityManager->getRepository(Developer::class)->getDevelopersByGender();
            $totalProjects = $entityManager->getRepository(Project::class)->getUniqueClients(); 

            $data = [
                'projects_per_developer' => $projectsPerDeveloper,
                'developers_in_projects' => $developersInProjects,
                'average_age' => $averageAge,
                'developer_count_by_position' => $developerCountByPosition,
                'developers_by_gender' => $developersByGender,
                'total_projects' => $totalProjects,
            ];

             $statisticsObject = (object) $data;


        return new JsonResponse($statisticsObject, Response::HTTP_CREATED);
    }
  }
