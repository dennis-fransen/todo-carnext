<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task implements JsonSerializable
{

    public const OPEN   = 'open';
    public const CLOSED = 'done';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dueDate;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime $dueDate
     */
    public function setDueDate(DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->getId(),
            'title'       => $this->getTitle(),
            'status'      => $this->getStatus(),
            'description' => $this->getDescription(),
        ];
    }
}
