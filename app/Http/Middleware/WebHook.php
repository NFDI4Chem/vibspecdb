<?php
 
namespace App\Http\Middleware;

use App\Actions\Webhook\WebhookAuth;
 
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

        $hookAuth = new WebhookAuth();

        $input = $request->all();
        $auth = $hookAuth->decrypt($request->input('token'));

        if (!$auth) {
          abort(403);
        }

        $input['type'] = $auth->type ?? '';
        $input['owner_id'] = $auth->owner_id ?? -1;
        $request->replace($input);
        

        if ($auth->key !== config('webhooks.argo_key') 
          || $auth->secret !== config('webhooks.argo_secret')) 
        {
          abort(403);
        }
 
        return $next($request);
    }

}