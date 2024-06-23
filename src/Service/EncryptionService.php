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

        //key missing

        $this->setData(openssl_encrypt($this->dataDecoded, $this->cipher, $this->getKey(), 0, $iv, $tag));
        $this->setFlags(implode(':', [urlencode($iv), urlencode($tag)]));
    }

    public function decode() : ?string
    {
        if ($this->dataDecoded) return $this->dataDecoded;
        if (!$this->getData()) return null;
        $this->dataDecoded = openssl_decrypt($this->getData(), $this->cipher, $this->getKey(), $options=0, $this->getFlagsArray()[0], $this->getFlagsArray()[1]);
        return $this->dataDecoded;
    }

}