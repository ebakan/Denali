<?php 
require_once("config.inc.php");
class cookie {
    public function authenticate( $user, $password, $remember ) {

        $sql = "SELECT * FROM `jelogins` WHERE `username` = '%s'";

        $result = mysql_query(sprintf($sql, $user)) or die ("Can't query");

        if ( mysql_fetch_array($result) > 0 ) {
            $user = $result[0];
        } else {
            throw new Exception( "This name was not found in the database." );

        $this->setCookie( $user["id"], $remember );
        }
    }
 
    public function setCookie( $id, $remember = false ) {

        if ( $remember ) {
            $expiration = time() + 1209600; // 14 days
        } else {
            $expiration = time() + 3600; //1 hour //172800; // 48 hours
        }

        $cookie = $this->generateCookie( $id, $expiration );

        if ( !setcookie( COOKIE_AUTH, $cookie, $expiration) ) {
            throw new Exception( "Could not set cookie." );
        }
    }

    public function generateCookie( $id, $expiration ) {

        $key = hash_hmac( 'md5', $id . $expiration, SECRET_KEY );
        $hash = hash_hmac( 'md5', $id . $expiration, $key );

        $cookie = $id . '|' . $expiration . '|' . $hash;

        return $cookie;
    }

    public function verifyCookie() {

        if ( empty($_COOKIE[COOKIE_AUTH]) )
            return false;

        list( $id, $expiration, $hmac ) = explode( '|', $_COOKIE[COOKIE_AUTH] );

        $expired = $expiration;

        if ( $expired < time() )
            return false;

        $key = hash_hmac( 'md5', $id . $expiration, SECRET_KEY );
        $hash = hash_hmac( 'md5', $id . $expiration, $key );

        if ( $hmac != $hash )
            return false;

        return true;

    }
}
?>
