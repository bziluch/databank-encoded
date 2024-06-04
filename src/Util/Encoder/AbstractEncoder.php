<?php

namespace App\Util\Encoder;

abstract class AbstractEncoder
{
    /*
     * TODO:    Create entity which will have content, key and other things necessary for encoding
     * TODO:    Create abstract entity with non-database field, which will be extended by entity mentioned above
     * TODO:    Move this to service, and add loading keys from env.
     * TODO:    Process entire encoding/decoding on events (onLoad and preFlush/postPersist)
     * TODO:    Create database copy, then create migration only for new fields and relations
     * TODO:    Create command for rewriting whole thing (decoding using old key, write to new entity, check if contents are the same compared to old)
     * TODO:    Create migration with condition (execute only if new fields are already rewrote)
     * TODO:    Adjust getters and setters to new content
     */

    private ?string $dataDecoded = null;
    private string $cipher = "aes-128-gcm";

    private function getFlagsArray() : array
    {
        $flags = explode(':', $this->getFlags());
        if (sizeof($flags) == 2) {
            return array_map(function ($el) { return urldecode($el); }, $flags);
        } else {
            return [
                0 => '',
                1 => ''
            ];
        }
    }

    abstract protected function getKey() : string;
    abstract protected function getData() : ?string;
    abstract protected function setData(string $data) : void;
    abstract protected function getFlags() : ?string;
    abstract protected function setFlags(string $flags) : void;

    protected function setCipher(string $cipher) : void { $this->cipher = $cipher; }

    public function encode(string $content) : void
    {
        $this->dataDecoded = $content;
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
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