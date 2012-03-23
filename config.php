    <?php

    //method calling syntax needs checking, variables as well

    //Error Codes:
    // 0: failed database login
    // 1: failed ldap login
// 2: incorrect user info
// 3: no id matches user

require_once("config.inc.php");

//login to the database    
function db_login()
{
	global $sql_server, $sql_username, $sql_password, $db_name;
	$db_link = mysql_connect($sql_server, $sql_username, $sql_password);
	if (!$db_link) {
        //header("Location: login.php?error=2");
        echo "DB_LINK failed";
	}
	$current_db = mysql_select_db($db_name, $db_link);
	if (!$current_db) {
        //header("Location: login.php?error=2");
        echo "selectDB FAILED";
	}
}

//returns true if login matches database info otherwise returns false
function test_login($username, $id)
{
    //if username or pass is blank return false
    if(strcmp($username, "") === 0 || strcmp($id, "") === 0)
        return false;
        
	db_login();

    //will accept with @bcp.org or without
    $email = $username;
    if(strcmp(substr($email, -8), "@bcp.org") != 0)
    {
        //echo("Suffix: ".substr($email, -8));
        $email = $email."@bcp.org";
    }

	$get_user_query = "SELECT * FROM studentdata WHERE StudentEmail='".$email."'";
	$query_result = mysql_query($get_user_query);
	if(!$query_result)
        return false;

	$active_user = mysql_fetch_assoc($query_result);
	{
		if($id != $active_user["BCPStudID"])
			return FALSE;
	}	
	return TRUE;
}

/**
 * So this is our new system - it's no longer unnecessarily a singleton and returns actual
 * objects :)
 */
class system
{
    private $connection;
    private $eventstable;
    private $registrationstable;
    private $studentdatatable;
    /** Having an empty constructor and just declaring names in here makes it easy
     *  In the future this might be changed to be more extensible, but I'm lazy
     */
    public function __construct() {
        // Values are read in from the config.inc.php file
        $this->connection = mysql_connect($GLOBALS["sql_server"],
            $GLOBALS["sql_username"], $GLOBALS["sql_password"])
            or die('Could not connect: '.mysql_error());
        $select = mysql_select_db($GLOBALS["db_name"], $this->connection)
            or die('Could not access database '.$GLOBALS["db_name"].': '.mysql_error());
        $this->eventstable = mysql_real_escape_string($GLOBALS["table_events"]);
        $this->registrationstable = mysql_real_escape_string($GLOBALS["table_registrations"]);
        $this->studentdatatable = mysql_real_escape_string($GLOBALS["table_studentdata"]);
    }

    // Student-facing functions
    // For use on the student-facing site

    /**
     * Get all the current events which aren't already full
     * Would be used when showing a student which slots he can sign up for
     * @param id the student id
     * @return a 3D array of timeslot -> 
     *                       array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the number of people signed up for it
     *         or false on error
     */
    public function getValidEvents($id) {
        $id = mysql_escape_string($id);
        $outArray = array();
        for($i = 1; $i <= 4; $i++) {
            $result = $this->getValidEventsByTimeslot($id, $i);
            if(!$result) {
                return false;
            }
            $outArray[$i] = $result;
        }
        return $outArray;
    }

    /**
     * Get all the current events for a timeslot which aren't already full
     * Would be used when asking a student to change a single timeslot registration
     * @param id the student id
     * @param timeslot the timeslot to list events for
     * @return a 2D array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the number of people signed up for it
     *         or false on error
     */
    public function getValidEventsByTimeslot($id, $timeslot) {
        $id = mysql_escape_string($id);
        $timeslot = mysql_escape_string($timeslot);
        $events = $this->eventstable;
        $query = "SELECT *, ".
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id) as count ".
            "FROM $events WHERE timeslot=$timeslot AND (SELECT COUNT(*) FROM ".$this->registrationstable.
            " WHERE event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id)<capacity";
        return $this->query2D($query);
    }

    /**
     * Get the events a student has registered for
     * Would be called once the student has logged in to check whether he is checking his
     * registrations or registering for his events
     * @param idnum the id number of the student
     * @return 2D array of timeslot -> associative array of data for the event
     *         if the user has registered, or false if he hasn't
     */
    public function getStudentRegistrations($idnum) {
        $idnum = mysql_escape_string($idnum);
        $outArray = array();
        for($i = 1; $i <= 4; $i++) {
            $query = "SELECT * FROM ".$this->eventstable." WHERE id IN ".
                "(SELECT event$i FROM ".$this->registrationstable." WHERE id=$idnum)";
            $result = mysql_query($query);
            if(!$result) {
                return false;
            }
            if(mysql_num_rows($result)) {
                $outArray[$i] = mysql_fetch_assoc($result);
            }
        }
        if(count($outArray) == 0) {
            return false;
        }
        return $outArray;
    }

    /**
     * Registers a student and his 4 events
     * Used after the student has confirmed his events
     * Pass in a value of 0 for one of the eid's if a student
     * doesn't have a course then (due to a preceeding double course)
     *
     * @param idnum the student's id number
     * @param eid1 the id of event #1
     * @param eid2 the id of event #2
     * @param eid3 the id of event #3
     * @param eid4 the id of event #4
     * @return boolean representing the success of the query
     */
    public function registerStudent($idnum, $eid1, $eid2, $eid3, $eid4) {
        $idnum = mysql_escape_string($idnum);

        if(!$eid1) {
            $eid1 = 'NULL';
        } else {
            $eid1 = mysql_escape_string($eid1);
        }

        if(!$eid2) {
            $eid2 = 'NULL';
        } else {
            $eid2 = mysql_escape_string($eid2);
        }

        if(!$eid3) {
            $eid4 = 'NULL';
        } else {
            $eid3 = mysql_escape_string($eid3);
        }

        if(!$eid4) {
            $eid4 = 'NULL';
        } else {
            $eid4 = mysql_escape_string($eid4);
        }

        $query = "INSERT INTO ".$this->registrationstable.
            " (id, event1, event2, event3, event4, timestamp) ".
            "VALUES ($idnum, $eid1, $eid2, $eid3, $eid4, NOW())";
        return mysql_query($query);
    }

    // Admin-facing functions
    // For use on the admin-facing site

    /**
     * Get all the current events
     * Would be used on the admin/checker page to list all events
     * @return a 3D array of timeslot -> 
     *                       array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the number of people signed up for it
     */
    public function getAllEvents() {
        $outArray = array();
        for($i = 1; $i <= 4; $i++) {
            $result = $this->getAllEventsByTimeslot($i);
            if(!$result) {
                return false;
            }
            $outArray[$i] = $result;
        }
        return $outArray;
    }

    /**
     * Get all the current events for a timeslot which aren't already full
     * Would be used when asking a student to change a single timeslot registration
     * @return a 2D array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the number of people signed up for it
     */
    public function getAllEventsByTimeslot($timeslot) {
        $timeslot = mysql_escape_string($timeslot);
        $events = $this->eventstable;
        $query = "SELECT *, ".
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id) ".
            "as count FROM $events WHERE timeslot=$timeslot";
        return $this->query2D($query);
    }

    /**
     * Gets a list of the users registered for a given event
     * Used in the summary page for each event
     * @param eid the event id
     * @return a 2D array of index -> data on registered student
     */
    public function getRegisteredStudents($eid) {
        $eid = mysql_escape_string($eid);
        $query = "SELECT * FROM ".$this->registrationstable." WHERE ".
            "event1=$eid OR event2=$eid OR event3=$eid OR event4=$eid";
        return $this->query2D($query);
    }

    /**
     * Checks if an event id is valid (exists and is not full)
     * Used when validating a student's event request
     * @param eid the event id
     * @return boolean representing the validity of the eid
     */
    public function isEventValid($eid) {
        $eid = mysql_escape_string($eid);
        if(strlen($eid)<1) {
            return true;
        }
        $events = $this->eventstable;
        $query = "SELECT * FROM $events WHERE id=$eid AND (SELECT COUNT(*) FROM ".
            $this->registrationstable." WHERE event1=$events.id OR event2=$events.id OR ".
            "event3=$events.id OR event4=$events.id)<capacity";
        $result = mysql_query($query);
        return mysql_num_rows($result) > 0;
    }


    // Private helper functions

    /**
      * Performs the query and returns data in a 2D array
      * @param query the query
      * @return 2D array of rows, each of which contains an associative array or false if unsuccessful
      */
    private function query2D($query) {
        $result=mysql_query($query,$this->connection);
        if(!$result)
            return false;
        $outArray=array();
        while($row=mysql_fetch_assoc($result)) {
            $outArray[]=$row;
        }
        return $outArray;
    }

}
