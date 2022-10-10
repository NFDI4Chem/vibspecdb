<?php

namespace App\Actions\Webhook;


class WebhookAuth
{

    protected $CONFIG;

    public function __construct()
    {
      $this->CONFIG = [
        'pass' => config('app.key'),
        'method' => 'aes-128-cbc',
        'initVector' => "7257863121582479",
      ];
    }

    public function encrypt(array $data)
    {
      $userDataJSON   = json_encode(
        array_merge(
          $data,
          [
            'key' => config('webhooks.argo_key') ,
            'secret' => config('webhooks.argo_secret')
          ]
        )
      );

      return openssl_encrypt(
        $userDataJSON,
        $this->CONFIG['method'], 
        $this->CONFIG['pass'], 
        false, 
        $this->CONFIG['initVector']
      );
    }

    public function decrypt(string $encrypted)
    {
      $decrypted = openssl_decrypt(
        $encrypted,
        $this->CONFIG['method'], 
        $this->CONFIG['pass'], 
        false, 
        $this->CONFIG['initVector']
      );
      return json_decode($decrypted);
    }
}
