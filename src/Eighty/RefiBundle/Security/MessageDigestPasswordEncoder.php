<?php

// custom encoder - joseph@eightydigital.com

namespace Eighty\RefiBundle\Security;
 
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder as BaseMessageDigestPasswordEncoder;
 
class MessageDigestPasswordEncoder extends BaseMessageDigestPasswordEncoder
{
    private $algorithm;
    private $encodeHashAsBase64;
 
    public function __construct($algorithm = 'sha1', $encodeHashAsBase64 = false, $iterations = 1)
    {
        $this->algorithm = $algorithm;
        $this->encodeHashAsBase64 = $encodeHashAsBase64;
        $this->iterations = $iterations;
    }
 
    protected function mergePasswordAndSalt($password, $salt)
    {
        if (empty($salt)) {
            return $password;
        }
 
        return $password.$salt;
    }
 
    public function encodePassword($raw, $salt)
    {
        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }
 
        $salted = $this->mergePasswordAndSalt($raw, $salt);
        $digest = hash($this->algorithm, $salted);
		
		return $digest;
	}
}