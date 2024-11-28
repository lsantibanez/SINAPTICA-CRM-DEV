<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Util_Jwt
{
  private $privateKey;
  private $publicKey;

  public function __construct()
  {
    $this->privateKey = file_get_contents(__DIR__.'/files/private.key','r');
    $this->publicKey  = file_get_contents(__DIR__.'/files/public.key','r');
  }

  public function encode($payload)
  {
    JWT::$leeway = 60;
    $issuedAt = new \DateTimeImmutable();
    $expire   = $issuedAt->modify('+15 minutes')->getTimestamp();

    unset($payload['iat'], $payload['exp'], $payload['nbf']);
    $payload['iat'] = $issuedAt->modify('-5 minutes')->getTimestamp();
    $payload['exp'] = $expire;
    $payload['nbf'] = $issuedAt->modify('-5 minutes')->getTimestamp();
    $payload['iss'] = '*.sinaptica.io';
    return JWT::encode($payload, $this->privateKey, 'RS256');
  }

  public function decode($token)
  {
    return JWT::decode($token, new Key($this->publicKey, 'RS256'));
  }
}