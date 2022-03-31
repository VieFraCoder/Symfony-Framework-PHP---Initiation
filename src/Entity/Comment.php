<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 1000)]
    private $message;

    #[ORM\Column(type: 'datetime', options:['default' => 'CURRENT_TIMESTAMP'])]
    private $executed_at;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $post;

    public function __construct()
    {
        $this->executed_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getExecutedAt(): ?\DateTimeInterface
    {
        return $this->executed_at;
    }

    public function setExecutedAt(\DateTimeInterface $executed_at): self
    {
        $this->executed_at = $executed_at;

        return $this;
    }

    public function getPost(): ?Posting
    {
        return $this->post;
    }

    public function setPost(?Posting $post): self
    {
        $this->post = $post;

        return $this;
    }
}
