<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column]
    private ?bool $secure = false;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Thread::class)]
    private Collection $threads;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private EncryptableString $name;

    public function __toString(): string
    {
        return (string)$this->getName();
    }

    public function __construct()
    {
        $this->name = new EncryptableString();

        $this->children = new ArrayCollection();
        $this->threads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getNameObj()->getContentRaw();
    }

    public function setName(string $name): static
    {
        $this->getNameObj()->setContentRaw($name);

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
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

    /**
     * @return Collection<int, Thread>
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread): static
    {
        if (!$this->threads->contains($thread)) {
            $this->threads->add($thread);
            $thread->setCategory($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread): static
    {
        if ($this->threads->removeElement($thread)) {
            // set the owning side to null (unless already changed)
            if ($thread->getCategory() === $this) {
                $thread->setCategory(null);
            }
        }

        return $this;
    }

    private function getNameObj(): EncryptableString
    {
        return $this->name;
    }
}
