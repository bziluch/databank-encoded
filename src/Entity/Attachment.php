<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'attachments')]
    private Collection $post;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private EncryptableString $content2;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private EncryptableString $name2;

    public function __construct()
    {
        $this->content2 = new EncryptableString();
        $this->name2 = new EncryptableString();

        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): static
    {
        if (!$this->post->contains($post)) {
            $this->post->add($post);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        $this->post->removeElement($post);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->getNameObj()->getContentRaw();
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return null;
    }

    public function setUploadedFile(?UploadedFile $uploadedFile): static
    {
        if (!$uploadedFile) {
            return $this;
        }

        $realPath = $uploadedFile->getRealPath();
        $content = base64_encode(file_get_contents($realPath));

        $orginalFilename = $uploadedFile->getClientOriginalName();
        $this->mimeType = $uploadedFile->getMimeType();

        $this->getContentObj()->setContentRaw($content);
        $this->getNameObj()->setContentRaw($orginalFilename);

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->getContentObj()->getContentRaw();
    }

    public function getContentBase64(): ?string
    {
        return 'data:'.$this->mimeType.';base64,'.$this->getContent();
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    private function getContentObj(): EncryptableString
    {
        return $this->content2;
    }

    private function getNameObj(): EncryptableString
    {
        return $this->name2;
    }
}
