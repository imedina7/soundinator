<?php 
require_once __DIR__ . "../lib/clients/DatabaseConnect.php";

class Auth {
    
    function __construct () {

    }

    private function create_session() {

        if ($session_id = openssl_random_pseudo_bytes(40)){
            
        } else {
           return false;
        }
        return false;
    }
    
    public static function validate_user($user,$pass) {
        $db = DatabaseConnect::geInstance();
        if ($db->userExists($user)) {
            $passHash = password_hash($pass, PASSWORD_BCRYPT);
            if ($db->userAuth($user, $passHash)){
                return create_session();
            }
        }
        return false;
    }
    public static function validate_session($session_id) {
        return true;
    }
    public static function destroy_session($session_id) {
        return true;
    }
}

?>