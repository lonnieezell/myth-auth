
<?php namespace Myth\Auth\Authentication\Username;

class ValidationRules
{
     
     public function in_list(string $username, string &$error = null): bool
     {
          if($this->not_in_list($username) == false)
          {
               // Username restricted
               // The error message is same with registration username error message
               $error = 'Username has used! Choose another.';
               return false;
          }

          // Username unrestricted
          return true;
     }

     protected function not_in_list(string $username)
     {
          $list = config('Myth\Auth\Config\Auth')->restrictedUsername;

          if(empty($list) === false && in_array($username, $list) === true)
          {
               return false;
          }

          return true;
     }

}
