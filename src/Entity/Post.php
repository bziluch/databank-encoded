<?php

namespace App\Entity;

use App\Entity\Abstract\DateLoggableEntity;
use App\Repository\PostRepository;
use App\Trait\DataLoggableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post extends DateLoggableEntity
{
    use DataLoggableEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    #[ORM\ManyToMany(targetEntity: Attachment::class, mappedBy: 'post', cascade: ['persist'])]
    private Collection $attachments;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private EncryptableString $content;

    public function __construct()
    {
        $this->content = new EncryptableString();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): static
    {
        $this->thread = $thread;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->getContentObj()->getContentRaw();
    }

    public function setContent(string $content): static
    {
        $this->getContentObj()->setContentRaw($content);

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): static
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->addPost($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): static
    {
        if ($this->attachments->removeElement($attachment)) {
            $attachment->removePost($this);
        }

        return $this;
    }

    private function getContentObj(): EncryptableString
    {
        return $this->content;
    }
}
