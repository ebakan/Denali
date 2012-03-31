<?php 
class LdapUser 
{
    var $name = 'LdapUser';
    var $useTable = false;

    var $host       = '';
    var $port       = ;
    var $baseDn = '';
    var $user       = '';
    var $pass       = '';

    var $ds;

    $stud = $_SESSION['uname'];

    function __construct()
    {
    	//parent::__construct();
    	$this->ds = ldap_connect($this->host, $this->port);
    	ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    	ldap_bind($this->ds, $this->user, $this->pass) or die("FUU");
    }

    function __destruct()
    {
    	ldap_close($this->ds);
    } 

    function findAll($attribute = 'uid', $value = '*', $baseDn = '')
    {
	    $r = ldap_search($this->ds, $baseDn, $attribute . '=' . $value);

	    if ($r)
	    {
		//if the result contains entries with surnames,
		//sort by surname:
		ldap_sort($this->ds, $r, "sn");

		return ldap_get_entries($this->ds, $r);
	    }
    }

     
    function auth($cn, $password)
    {
	    $result = $this->findAll('mailNickname', $cn);

	    if($result[0])
	    {
		if (ldap_bind($this->ds, $result[0]['dn'], $password))
		    {
			return $result[0]['description'][0];
		    }
		    else
		    {
			return false;
		    }
	    }
	    else
	    {
		return false;
	    }
   } 
}
?> 
