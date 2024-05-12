<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use App\Util\Encoder\CategoryEncoder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    private static string $KEY = 'v6U0cH7AxrF4gOCBZfwsYJjVMz9Equoe';
    private ?CategoryEncoder $encoder = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $flags = null;

    #[ORM\Column]
    private ?bool $secure = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Thread::class)]
    private Collection $threads;

    #[ORM\Column(length: 255)]
    private ?string $encodeKey = null;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->encodeKey = bin2hex(random_bytes(12));
        $this->children = new ArrayCollection();
        $this->threads = new ArrayCollection();
        $this->initializeEncoder();
    }

    private function initializeEncoder()
    {
        if (!$this->encoder)
        {
            $this->encoder = new CategoryEncoder($this);
        }
    }

    public function getFullKey() : string
    {
        return $this->encodeKey.self::$KEY;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEncoded(): ?string
    {
        return $this->name;
    }

    public function setNameEncoded(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        $this->initializeEncoder();
        return $this->encoder->decode();
    }

    public function setName(string $name): static
    {
        $this->initializeEncoder();
        $this->encoder->encode($name);

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

    public function getFlags(): ?string
    {
        return $this->flags;
    }

    public function setFlags(string $flags): static
    {
        $this->flags = $flags;

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

    public function getEncodeKey(): ?string
    {
        return $this->encodeKey;
    }

    public function setEncodeKey(string $encodeKey): static
    {
        $this->encodeKey = $encodeKey;

        return $this;
    }
}
