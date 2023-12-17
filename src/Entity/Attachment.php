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
    private ?bool $flag1 = null;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    #[ORM\Column(length: 255)]
    private ?string $encodeKey = null;

    public function __construct()
    {
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
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getFlags(): ?string
    {
        return $this->flags;
    }

    public function setFlags(string $flags): static
    {
        $this->flags = $flags;

        return $this;
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

    private function base64_to_file( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" );
        fwrite( $ifp, base64_decode( $base64_string) );
        fclose( $ifp );
        return( $output_file );
    }

    public function setUploadedFile(UploadedFile $uploadedFile): static
    {
        $realPath = $uploadedFile->getRealPath();
        $data = base64_encode(file_get_contents($realPath));
        $orginalFilename = $uploadedFile->getClientOriginalName();
        $this->mimeType = $uploadedFile->getMimeType();

        dump($data);
        dump($orginalFilename);

//        $newFile = $this->base64_to_file($file, $orginalFilename);

        echo '<img src="data:'.$uploadedFile->getMimeType().';base64,' . $data . '" />';


//        $file = file_get_contents($realPath);
//        dump($file);
//        $encoded = base64_encode($file);

        dd($uploadedFile);

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

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
