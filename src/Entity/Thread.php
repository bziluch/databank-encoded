<?php

namespace App\Entity;

use App\Entity\Abstract\DateLoggableEntity;
use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
class Thread extends DateLoggableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\Column]
    private ?bool $secure = false;

    #[ORM\ManyToOne(inversedBy: 'threads')]
    private ?Category $category = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private EncryptableString $title;

    public function __construct()
    {
        $this->title = new EncryptableString();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->getTitleObj()->getContentRaw();
    }

    public function setTitle(string $title): static
    {
        $this->getTitleObj()->setContentRaw($title);

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setThread($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getThread() === $this) {
                $post->setThread(null);
            }
        }

        return $this;
    }

    public function isSecure(): ?bool
    {
        return $this->secure;
    }

    public function setSecure(bool $secure): static
    {
        $this->secure = $secure;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    private function getTitleObj(): EncryptableString
    {
        return $this->title;
    }

}
