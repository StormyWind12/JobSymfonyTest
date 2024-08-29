<?php
namespace App\Controller;

use App\Entity\Developer;
use App\Entity\DeveloperProject;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class DeveloperController extends AbstractController
{
    /**
    * createDeveloper - Содание новых пользователей
    *
    * Данная функция создает записи о новых пользователях
    * http://localhost:8080/developer POST
    *{"fullName":"*** *** ***", "email": "***@****.**","phone":+7***, "position": "дизайнер/программист/администратор/devops", "gender": "мужской\женский", "age":18-80 }
    */
    #[Route('/developer', name: 'app_developer_create', methods: ['POST'])]
    public function createDeveloper(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $developer = new Developer();
        $developer->setFullName($data['fullName']);
        $developer->setEmail($data['email']);
        $developer->setPhone($data['phone']);
        $developer->setPosition($data['position']);
        $developer->setGender($data['gender']);
        $developer->setAge($data['age']);
        
        // Проверка валидации
        $errors = $validator->validate($developer);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        // Сохранение разработчика
        $entityManager->persist($developer);
        $entityManager->flush();

        return new Response('Новый пользователь успешно создан', Response::HTTP_CREATED);
    }

  /**
    * deleteDeveloper - Удаление пользователя
    *
    * Данная функция удаляет пользователя по его индентификатору, если ему есть назначенные проекты, связь с ними тоже пропадает
    *
    */
   
    #[Route('/developer/{id}', name: 'app_developer_delete', methods: ["DELETE"])]
    public function deleteDeveloper(int $id, EntityManagerInterface $entityManager): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);
        if (!$developer) {
            return new Response('Пользователь не найден', Response::HTTP_NOT_FOUND);
        }

        $resultsFind = $entityManager->getRepository(DeveloperProject::class)->findBy(["developer_id"=>$id]);
        foreach ($resultsFind as $result) {
            $entityManager->remove($result);
        }

        $entityManager->remove($developer);
        $entityManager->flush();

        return new Response('Пользователь успешно удалён', Response::HTTP_OK);
    }


  /**
    * updateDeveloper - Обновление информации о пользователе
    *
    * Данная функция обновляет информацию по его идентификатору
    *
    */

    #[Route('/developer/{id}', name: 'app_developer_update', methods: ['PUT'])]
    public function updateDeveloper(int $id, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            return new Response('Developer not found', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $developer->setFullName($data['fullName'] ?? $developer->getFullName());
        $developer->setEmail($data['email'] ?? $developer->getEmail());
        $developer->setPhone($data['phone'] ?? $developer->getPhone());
        $developer->setPosition($data['position'] ?? $developer->getPosition());
        $developer->setGender($data['gender'] ?? $developer->getGender());
        $developer->setAge($data['age'] ?? $developer->getAge());
        // Проверка валидации
        $errors = $validator->validate($developer);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        // Обновление разработчика
        $entityManager->flush();

        return new Response('Информация о пользователе обновлена', Response::HTTP_OK);
    }
  
}
