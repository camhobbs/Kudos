<?php
declare(strict_types=1);
namespace CamHobbs\Kudos\Utils;

final class SessionHandler
{
  private function __construct()
  {
  }

  private function __clone()
  {
  }

  static function setupForUser(string $userId)
  {
    \session_start();

    self::setSessionVars(array(
      "userId" => $userId,
      "csrfToken" => \bin2hex(\random_bytes(32))
    ));
  }

  static function setSessionVars($sessionVars, $secure = true) {
    foreach($sessionVars as $key => $value) {
      $_SESSION[$key] = $value;
    }
    if($secure) {
      \session_regenerate_id();
    }
  }

  static function destroy()
  {
    if(isset($_SESSION)) {
      \session_destroy();
    }
  }
}
