<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    private static string $KEY = 'ZhcazV1EwmbPF7tyC0xku4qY';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'attachments')]
    private Collection $post;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $flags = null;

    #[ORM\Column]
    private ?bool $flag1 = false;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    #[ORM\Column(length: 255)]
    private ?string $encodeKey = null;

    public function __construct()
    {
        $this->encodeKey = bin2hex(random_bytes(12));
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
        $flags = $this->getFlagsArray();
        if (sizeof($flags) < 4) {
            return null;
        }
        $cipher = "aes-128-gcm";
        return openssl_decrypt($this->name, $cipher, $this->encodeKey.self::$KEY, $options=0, $flags[2], $flags[3]);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getFlags(): ?string
    {
        return $this->flags;
    }

    public function getFlagsArray(): array
    {
        if (!$this->flags) {
            return [];
        }
        $flags = explode(':', $this->flags);
        return array_map(function ($el) { return urldecode($el); }, $flags);
    }

    public function isFlag1(): ?bool
    {
        return $this->flag1;
    }

    public function setFlag1(bool $flag1): static
    {
        $this->flag1 = $flag1;

        return $this;
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

        $cipher = "aes-128-gcm";

        $ivlenContent = openssl_cipher_iv_length($cipher);
        $ivContent = openssl_random_pseudo_bytes($ivlenContent);
        $this->content = openssl_encrypt($content, $cipher, $this->encodeKey.self::$KEY, $options=0, $ivContent, $tagContent);

        $ivlenName = openssl_cipher_iv_length($cipher);
        $ivName = openssl_random_pseudo_bytes($ivlenName);
        $this->name = openssl_encrypt($orginalFilename, $cipher, $this->encodeKey.self::$KEY, $options=0, $ivName, $tagName);

        $this->flags = implode(':', [urlencode($ivContent), urlencode($tagContent), urlencode($ivContent), urlencode($tagContent)]);

//        echo '<img src="data:'.$uploadedFile->getMimeType().';base64,' . $data . '" />';
        return $this;
    }

    public function getContentBase64(): ?string
    {
        $flags = $this->getFlagsArray();
        if (sizeof($flags) < 4) {
            return null;
        }
        $cipher = "aes-128-gcm";
        $content = openssl_decrypt($this->content, $cipher, $this->encodeKey.self::$KEY, $options=0, $flags[0], $flags[1]);
        return 'data:'.$this->mimeType.';base64,'.$content;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getEncodeKey(): ?string
    {
        return $this->encodeKey;
    }
}
