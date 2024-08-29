<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeveloperRepository::class)]
class Developer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'ФИО не должен быть пустым.')]
    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Электронная почта не может быть пустой')]
    #[Assert\Email(
        message: 'Данный e-mail {{ value }} не является действительным электронным письмом.',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 20,
        maxMessage: 'Контактный телефон не должен превышать {{ limit }} символов.'
    )]
    #[Assert\Type(type: 'string', message: 'Контактный телефон должен быть строкой.')]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]*$/',
        message: 'Контактный телефон должен содержать только цифры и, возможно, знак "+".'
    )]
    #[Assert\NotBlank(message: 'Номер телефона не должен быть пустой.')]

    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type(
        type: 'string',
        message: 'Значение {{ value }} не является подходящим типом данных {{ type }}.',
    )]
    #[Assert\NotBlank(message: 'Должность не должна быть пустой.')]
    #[Assert\Choice(
        choices: ['программист', 'администратор', 'devops', 'дизайнер'],
        message: 'Выберите действительную должность.'
    )]
     private ?string $Position = null;
    
     #[Assert\Choice(
         choices: ['мужской', 'женский'],
         message: 'Выберите пол'
     )]
    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(length: 10)]
    #[Assert\Range(
        min: 18,
        max: 80,
        notInRangeMessage: 'Возраст который вы указали {{ value }}, не возможен',
    )]
    #[Assert\Positive]
    private ?int $age = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    } 

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
    
    public function getPosition(): ?string
    {
        return $this->Position;
    }

    public function setPosition(string $Position): static
    {
        $this->Position = $Position;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }
}
