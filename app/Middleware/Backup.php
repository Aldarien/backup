<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Backup
{
  protected $settings;
  public function __construct($app = null)
  {
    $this->settings = $app->getContainer()->get('settings')['backup'];
  }
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
  {
    // Check for last backup
    $backup = new \App\Service\Backup($this->settings);
    if (!$backup->hasBackup()) {
      $backup->extract();
      $backup->save();
    }
    return $next($request, $response);
  }
}
