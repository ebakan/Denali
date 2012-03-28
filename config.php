<?php
require_once("config.inc.php");

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
    private $logtable;
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
        $this->logtable = mysql_real_escape_string($GLOBALS["table_log"]);
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
        // Work backwards because we need it to
        for ($i = 4; $i >= 1; $i--) {
            // Disable long events if there's a required event in the next one
            $noLongs = false;
            if ($i<4) {
                foreach($outArray[$i+1] as $event) {
                    if(!is_null($event['required'])) {
                        $noLongs=true;
                        break;
                    }
                }
            }
            $result = $this->getValidEventsByTimeslot($id, $i, $noLongs);
            if (!$result) {
                return false;
            }
            $outArray[$i] = $result;
        }
        return $outArray;
    }

    /**
     * Get all the current events which aren't already full
     * Sorts by "fullness" ascending
     * Would be used when showing a student which slots he can sign up for
     * @param id the student id
     * @return a 3D array of timeslot -> 
     *                       array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the fullness
     *         or false on error
     */
    public function getValidEventsRandom($id) {
        $id = mysql_escape_string($id);
        $outArray = array();
        // Work backwards because we need it to
        for ($i = 4; $i >= 1; $i--) {
            // Disable long events if there's a required event in the next one
            $noLongs = false;
            if ($i<4) {
                foreach($outArray[$i+1] as $event) {
                    if(!is_null($event['required'])) {
                        $noLongs=true;
                        break;
                    }
                }
            }
            $result = $this->getValidEventsByTimeslotRandom($id, $i, $noLongs);
            if (!$result) {
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
     * @param noLongs whether long events should be disabled or not
     * @return a 2D array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       the number of people signed up for it
     *         or false on error
     */
    public function getValidEventsByTimeslot($id, $timeslot, $noLongs = false) {
        $id = mysql_escape_string($id);
        $year = substr($id,1,2);
        $grade = 24-intval($year);
        $timeslot = mysql_escape_string($timeslot);
        $events = $this->eventstable;
        // Check if there is a restricted event for this person
        // mMOAQ - mini Mother Of All Queries
        // Basic select
        $query = "SELECT *, ".
            // Subquery for count
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id) as count ".
            // Check for timeslot and the restricted year
            "FROM $events WHERE timeslot=$timeslot AND required=$grade";
        $restricted = $this->query2D($query);
        // Return this if there is a result
        if($restricted && count($restricted)>0) {
            return $restricted;
        }
        // No restricted events, do the full MOAQ - mother of all queries
        // Basic select
        $query = "SELECT *, ".
            // Add subquery for count
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id) as count ".
            // Check for timeslot and not a required event (for other classes)
            "FROM $events WHERE timeslot=$timeslot AND required IS NULL ".
            // Make sure the event is not restricted or we have access to it
            "AND (restricted IS NULL OR FIND_IN_SET('$grade',restricted)>0) ".
            // Make sure capacity is less than 0 (unlimited) or the current count is less than the capacity
            "AND (SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id)<capacity";
        // Disable long events
        if($noLongs) {
            $query .= " AND length<100";
        }
        return $this->query2D($query);
    }

    /**
     * Get all the current events for a timeslot which aren't already full
     * Sorts by "fullness" ascending
     * Would be used when asking a student to change a single timeslot registration
     * @param id the student id
     * @param timeslot the timeslot to list events for
     * @param noLongs whether long events should be disabled or not
     * @return a 2D array of available events for the timeslot ->
     *                       associative array of database data for each event plus
     *                       fullness
     *         or false on error
     */
    public function getValidEventsByTimeslotRandom($id, $timeslot, $noLongs = false) {
        $id = mysql_escape_string($id);
        $year = substr($id,1,2);
        $grade = 24-intval($year);
        $timeslot = mysql_escape_string($timeslot);
        $events = $this->eventstable;
        // Check if there is a restricted event for this person
        // mMOAQ - mini Mother Of All Queries
        // Basic select
        $query = "SELECT *, ".
            // Subquery for count
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id) as count ".
            // Check for timeslot and the restricted year
            "FROM $events WHERE timeslot=$timeslot AND required=$grade";
        $restricted = $this->query2D($query);
        // Return this if there is a result
        if($restricted && count($restricted)>0) {
            return $restricted;
        }
        // No restricted events, do the full MOAQ - mother of all queries
        // Basic select
        $query = "SELECT *, ".
            // Add subquery for count
            "(SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id)/capacity as fullness ".
            // Check for timeslot and not a required event (for other classes)
            "FROM $events WHERE timeslot=$timeslot AND required IS NULL ".
            // Make sure the event is not restricted or we have access to it
            "AND (restricted IS NULL OR FIND_IN_SET('$grade',restricted)>0) ".
            // Make sure capacity is less than 0 (unlimited) or the current count is less than the capacity
            "AND (capacity<0 OR (SELECT COUNT(*) FROM ".$this->registrationstable." WHERE ".
            "event1=$events.id OR event2=$events.id OR event3=$events.id OR event4=$events.id)<capacity)";
        // Disable long events
        if($noLongs) {
            $query .= " AND length<100";
        }
        $query .= "ORDER BY fullness ASC";
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
        for ($i = 1; $i <= 4; $i++) {
            $query = "SELECT * FROM ".$this->eventstable." WHERE id IN ".
                "(SELECT event$i FROM ".$this->registrationstable." WHERE id=$idnum)";
            $result = mysql_query($query);
            if (!$result) {
                return false;
            }
            if (mysql_num_rows($result)) {
                $outArray[$i] = mysql_fetch_assoc($result);
            }
        }
        if (count($outArray) == 0) {
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
        $useragent = mysql_escape_string($_SERVER['HTTP_USER_AGENT']);
        $ipaddr = mysql_escape_string($_SERVER['REMOTE_ADDR']);

        if (!$eid1) {
            $eid1 = 'NULL';
        } else {
            $eid1 = mysql_escape_string($eid1);
        }

        if (!$eid2) {
            $eid2 = 'NULL';
        } else {
            $eid2 = mysql_escape_string($eid2);
        }

        if (!$eid3) {
            $eid4 = 'NULL';
        } else {
            $eid3 = mysql_escape_string($eid3);
        }

        if (!$eid4) {
            $eid4 = 'NULL';
        } else {
            $eid4 = mysql_escape_string($eid4);
        }

        $query = "INSERT INTO ".$this->registrationstable.
            " (id, event1, event2, event3, event4, timestamp, ipaddr, useragent) ".
            "VALUES ($idnum, $eid1, $eid2, $eid3, $eid4, NOW(), '$ipaddr', '$useragent')";
        return mysql_query($query);
    }

    /**
     * Logs a student with an error into the database
     * @param $id the user id of the student
     * @param $err a string that represents an error
     * @return either true or false
     */
    public function logStudent($id, $err)
    {
        $query = "INSERT INTO ".$this->logtable.
            " (uid, error, timestamp) ".
            "VALUES ($id, $err, NOW())";
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
        for ($i = 1; $i <= 4; $i++) {
            $result = $this->getAllEventsByTimeslot($i);
            if (!$result) {
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
        $query = "SELECT * FROM ".$this->studentdatatable." WHERE BCPStudID IN (SELECT id FROM ".
            $this->registrationstable." WHERE event1=$eid OR event2=$eid OR event3=$eid OR event4=$eid)";
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
        if (strlen($eid)<1) {
            return true;
        }
        $events = $this->eventstable;
        $query = "SELECT * FROM $events WHERE id=$eid AND (capacity<0 OR (SELECT COUNT(*) FROM ".
            $this->registrationstable." WHERE event1=$events.id OR event2=$events.id OR ".
            "event3=$events.id OR event4=$events.id)<capacity)";
        $result = mysql_query($query);
        return mysql_num_rows($result) > 0;
    }

    /**
     * Gets the info on a student
     * Used on the event chekcer
     * @param id the student id
     * @return an array of student info from the database
     */
    public function getStudentInfo($id) {
        $id = mysql_escape_string($id);
        $query = "SELECT * FROM ".$this->studentdatatable." WHERE BCPStudID=$id";
        $result = mysql_query($query);
        if(!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }


    // Private helper functions

    /**
      * Performs the query and returns data in a 2D array
      * @param query the query
      * @return 2D array of rows, each of which contains an associative array or false if unsuccessful
      */
    private function query2D($query) {
        $result = mysql_query($query ,$this->connection);
        if (!$result) {
            return false;
        }
        $outArray=array();
        while ($row = mysql_fetch_assoc($result)) {
            $outArray[] = $row;
        }
        return $outArray;
    }

    /** Return the first value of the first row of a query
      * @param query the query
      * @return the first value of the first row of the query
      */
    public function getFirstVal($query) {
        $result = mysql_query($query, $this->connection);
        if (!$result) {
            return false;
        }
        $arr = mysql_fetch_array($result);
        return $arr[0];
    }


}
