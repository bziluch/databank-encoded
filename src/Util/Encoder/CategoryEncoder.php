<?php

namespace App\Util\Encoder;

use App\Entity\Category;

class CategoryEncoder extends AbstractEncoder
{
    public function __construct(
        private readonly Category $category
    ){}

    protected function getKey(): string
    {
        return $this->category->getFullKey();
    }

    protected function getData(): ?string
    {
        return $this->category->getNameEncoded();
    }

    protected function setData(string $data): void
    {
        $this->category->setNameEncoded($data);
    }

    protected function getFlags(): ?string
    {
        return $this->category->getFlags();
    }

    protected function setFlags(string $flags): void
    {
        $this->category->setFlags($flags);
    }
}