<?php 
require_once __DIR__ . "/../clients/DatabaseConnect.php";
require_once __DIR__ . "/../clients/RedisConnect.php";

class Auth {
    
    function __construct () {

    }

    private function create_session($user_id) {

        if ($session_id = openssl_random_pseudo_bytes(40)){
            $rdis = RedisConnect::getInstance();
            if ($rdis->addSession($session_id,$user_id))
                return $session_id;
        } else {
           return false;
        }
        return false;
    }
    
    public static function validate_user( $user, $pass ) {
        $db = DatabaseConnect::geInstance();
        if ($db->userExists( $user )) {

            $passHash = password_hash($pass, PASSWORD_BCRYPT);

            if ($user_id = $db->userAuth( $user, $passHash ))

                return create_session( $user_id );

        }
        return false;
    }
    public static function validate_session( $session_id ) {

        $rdis = RedisConnect::getInstance();

        return $rdis->getSessionUserId( $session_id );

    }
    public static function destroy_session( $session_id ) {

        $rdis = RedisConnect::getInstance();

        return $rdis->deleteSession( $session_id );
    }
}

?>