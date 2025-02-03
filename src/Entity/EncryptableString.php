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
    private ?string $contentKey;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $iv = '';

    #[ORM\Column(type: Types::TEXT)]
    private ?string $tag = '';

    private bool $edited = false;

    public function __construct()
    {
        $this->contentKey = bin2hex(random_bytes(12));
    }

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

    public function getIv(): ?string
    {
        return urldecode($this->iv);
    }

    public function setIv(string $iv): static
    {
        $this->iv = urlencode($iv);

        return $this;
    }

    public function getTag(): ?string
    {
        return urldecode($this->tag);
    }

    public function setTag(string $tag): static
    {
        $this->tag = urlencode($tag);

        return $this;
    }

    public function getContentRaw(): ?string
    {
        return $this->contentRaw;
    }

    public function setContentRaw(string $contentRaw): static
    {
        $this->contentRaw = $contentRaw;
        $this->edited = true;

        return $this;
    }

    public function isEdited(): bool
    {
        return $this->edited;
    }
}
