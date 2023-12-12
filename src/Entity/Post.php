<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;
    private ?string $content_decrypted_ = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $iv = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $tag = null;

    public function __construct()
    {
        $this->createDate = new \DateTime('now');
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

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getContent(): ?string
    {
        if ($this->content_decrypted_)
        {
            return $this->content_decrypted_;
        }
        if (!$this->content)
        {
            return null;
        }
        $cipher = "aes-128-gcm";
        $this->content_decrypted_ = openssl_decrypt($this->content, $cipher, $this->thread->getEncodeKey(), $options=0, $this->getIv(), $this->getTag());
        return $this->content_decrypted_;
    }

    public function setContent(string $content): static
    {
        $this->content_decrypted_ = $content;
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $this->iv = openssl_random_pseudo_bytes($ivlen);
        $this->content = openssl_encrypt($content, $cipher, $this->thread->getEncodeKey(), $options=0, $this->iv, $this->tag);
        $this->iv = urlencode($this->iv);
        $this->tag = urlencode($this->tag);
        return $this;
    }

    public function getIv(): ?string
    {
        return urldecode($this->iv);
    }

    public function getTag(): ?string
    {
        return urldecode($this->tag);
    }
}
