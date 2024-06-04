<?php

namespace App\Entity;

use App\Repository\EncryptableStringRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncryptableStringRepository::class)]
class EncryptableString
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;
    private ?string $contentRaw = null;

    #[ORM\Column(length: 255)]
    private ?string $contentKey = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $iv = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getContentKey(): ?string
    {
        return $this->contentKey;
    }

    public function setContentKey(string $contentKey): static
    {
        $this->contentKey = $contentKey;

        return $this;
    }

    public function getIv(): ?string
    {
        return $this->iv;
    }

    public function setIv(string $iv): static
    {
        $this->iv = $iv;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getContentRaw(): ?string
    {
        return $this->contentRaw;
    }

    public function setContentRaw(string $contentRaw): static
    {
        $this->contentRaw = $contentRaw;

        return $this;
    }
}
