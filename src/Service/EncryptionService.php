<?php

namespace App\Service;

use App\Entity\EncryptableString;

class EncryptionService
{
    private string $cipher = "aes-128-gcm";

    public function __construct(
        private readonly string $keyPrefix
    ) {}

    public function encrypt(EncryptableString $encryptableString) : void
    {
        $ivLen = openssl_cipher_iv_length($this->cipher);
        $encryptableString->setIv(openssl_random_pseudo_bytes($ivLen));

        $encryptableString->setContent(openssl_encrypt(
            $encryptableString->getContentRaw(),
            $this->cipher,
            $this->keyPrefix . $encryptableString->getContentKey(),
            0,
            $encryptableString->getIv(),
            $tag
        ));

        $encryptableString->setTag($tag);
    }

    public function decrypt(EncryptableString $encryptableString) : void
    {
        if (null !== $encryptableString->getContentRaw() || null === $encryptableString->getContent()) {
            return;
        }

        $encryptableString->setContentRaw(openssl_decrypt(
            $encryptableString->getContentRaw(),
            $this->cipher,
            $this->keyPrefix . $encryptableString->getContentKey(),
            0,
            $encryptableString->getIv(),
            $encryptableString->getTag()
        ));
    }

}