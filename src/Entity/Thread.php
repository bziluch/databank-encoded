<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
class Thread
{
    private static string $KEY = 'Q4WGndjNZyg7cUtY8r53Hx96';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $encodeKey = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updateDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;
    private ?string $title_decrypted_ = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $iv = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $tag = null;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\Column]
    private ?bool $secure = false;

    public function __construct()
    {
        $this->encodeKey = bin2hex(random_bytes(12));
        $this->posts = new ArrayCollection();
    }

    public function _onSave()
    {
        if (!($this->createDate))
        {
            $this->createDate = new \DateTime('now');
        }
        $this->updateDate = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEncodeKey(): ?string
    {
        return $this->encodeKey;
    }

//    public function setEncodeKey(string $encodeKey): static
//    {
//        $this->encodeKey = $encodeKey;
//
//        return $this;
//    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getTitle(): ?string
    {
        if ($this->title_decrypted_)
        {
            return $this->title_decrypted_;
        }
        if (!$this->title)
        {
            return null;
        }
        $cipher = "aes-128-gcm";
        $this->title_decrypted_ = openssl_decrypt($this->title, $cipher, $this->encodeKey.self::$KEY, $options=0, $this->getIv(), $this->getTag());
        return $this->title_decrypted_;
    }

    public function setTitle(string $title): static
    {
        $this->title_decrypted_ = $title;
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $this->iv = openssl_random_pseudo_bytes($ivlen);
        $this->title = openssl_encrypt($title, $cipher, $this->encodeKey.self::$KEY, $options=0, $this->iv, $this->tag);
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

    public function getFullKey(): string
    {
        return $this->encodeKey.self::$KEY;
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

}
