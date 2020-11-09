<?php

namespace App\Core\Entities\Post;

use App\Core\Entities\Entity;
use App\Core\Entities\User\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repositories\PostsRepository")
 * @ORM\Table(
 *     name="posts",
 * )
 */
class Post extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $text;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    private DateTimeImmutable $createdAt;


    public function __construct(User $user, string $title, string $text)
    {
        $this->user = $user;
        $this->title = $title;
        $this->text = $text;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getUser(): User
    {
        return $this->user;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
