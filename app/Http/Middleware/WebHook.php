<?php
 
namespace App\Http\Middleware;
 
use Closure;
 
class WebHook
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

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $input = $request->all();
        $auth = $this->decrypt($request->input('token'));

        if (!$auth) {
          abort(403);
        }

        $input['type'] = $auth->type ?? '';
        $request->replace($input);
        

        if ($auth->key !== config('webhooks.argo_key') 
          || $auth->secret !== config('webhooks.argo_secret')) 
        {

          abort(403);
        }
 
        return $next($request);
    }

    private function decrypt($encrypted) { 
      $decrypted = openssl_decrypt(
        $encrypted,
        $this->CONFIG['method'], 
        $this->CONFIG['pass'], 
        false, 
        $this->CONFIG['initVector']
      );
      return json_decode($decrypted);
    }

    /*

    $in = [
      'type' => 'jobstatus',
      'key' => 'data',
      'secret' => 'data',
    ];

    $encrypted = $this->encrypt($in);

    */

    private function encrypt($data) {

      $userDataJSON   = json_encode($data);

      return openssl_encrypt(
        $userDataJSON,
        $this->CONFIG['method'], 
        $this->CONFIG['pass'], 
        false, 
        $this->CONFIG['initVector']
      );
    }
}