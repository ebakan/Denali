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
        header("Location: login.php?error=2");
	}
	$current_db = mysql_select_db($db_name, $db_link);
	if (!$current_db) {
        header("Location: login.php?error=2");
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

/* Main singleton  system class that deals directly with the database.
* each user will deal with a system object and everything will be
* screened before calling register($user) to make the changes
* final in the database
*/
class system
{
	private static $events; //array to be set in the constructor, indeces are the event ids and values are the slots available
	private static $instance;
    private static $users = array();
	
    //populates object with events that have slots left
	private function populate()
	{
		$eventData = mysql_query("SELECT * FROM `testschema`");
		$i = 1;
		while($current = mysql_fetch_assoc($eventData))
		{
			self::$events[$i] = $current["slotsAvailable"];
			$i = $i + 1;
		}
	}
	
    //singleton object return
	public function getInstance()
	{ 
		if(!self::$instance) 
		{
			self::$instance = new system(); 
            system::populate();
		}
		return self::$instance; 
	} 
	
    //returns a string of all valid events with slots
	public function getValidEvents()
	{
		$result = "";
		$i = 1;

        //if the reuslt string is empty, if there is at least 1 event available, if the first event has slot availabe
        if(strcmp($result, "") == 0 && count(self::$events) >= 1 && self::$events[$i] > 0)
        { $result = $result.$i; $i++; }
        
        
        for(; $i <= count(self::$events); $i++)
        {
            if(self::$events[$i] > 0)
            { $result = $result."|".$i; }
        }
        
		return $result;
	}
	
	//return long events in a string
	public function getLongEvents()
	{
		$event_query = "SELECT * FROM  `testschema` WHERE length=100";
		$event_table = mysql_query($event_query);
		$result = "";
		if(!$event_table)
		{
            header("Location: login.php?error=2");
		}
		else
		{
			while($row = mysql_fetch_assoc($event_table))
			{
				if(strlen($result) < 1)
					$result = $row['id'];
				else
					$result = $result."|".$row['id'];
			}
			return $result;
		}
		//must use mysql_fetch_assoc to cycle through on other side	
	}
	
	//returns long events from a certain time slot as a string
    //compares events in timeslot to all long events
	public function getLongFromTimeSlot($slot)
	{
		$timeSlot = getFromTimeSlot($slot);
		$long = getLongEvents();
		$count = 0;
		$result = "";
		$timeElements = explode("|", $timeSlot);
		$longElements = explode("|", $long);
		for($i = 0; $i < count($timeElements); $i++)
		{
			for($j = 0; $j < count($longElements); $j++)
			{
				if($timeElements[$i] === $longElements[$j])
				{
					if(strlen($result) < 1)
						$result = $timeElements[$i];
					else
						$result = $result."|".$timeElements[$i];
				}
			}
		}
		return $result;
	}
	
    //get events from time slot	
	public function getFromTimeSlot($slot)
	{
		$event_query = "SELECT * FROM  `testschema` WHERE timeslot=".$slot;
		$event_table = mysql_query($event_query);
		$result = "";
		if(!$event_table)
		{
            header("Location: login.php?error=2");
		}
		else
		{
			while($row = mysql_fetch_assoc($event_table))
			{
				if(strlen($result) < 1)
					$result = $row['id'];
				else
					$result = $result."|".$row['id'];
			}
			return $result;
		}
		//must use mysql_fetch_assoc to cycle through on other side	
	}

    public function getTitlesFromTimeSlot($slot)
    {
        $sql = "SELECT * from `testschema`";
        if(!($result = mysql_query($sql)))
            header("Location: login.php?error=2");

        $res = "";
        while($row = mysql_fetch_array($result)){
            $idsFromDB = $row['timeslot'];
            $ids = explode(",", $idsFromDB);
            for($i = 0; $i < sizeof($ids); $i++)
            {
                if($ids[$i] == $slot && !empty($res))
                    $res = $res."|".$row['title'];
                else if ($ids[$i] == $slot)
                    $res = $res.$row['title'];

            }
        }

        return $res;
        
    }
	
    //add one to the slotsAvailable in the system
	public function free($eventNum)
	{
		self::$events[$eventNum]++;
	}
	
    //remove one from the slotsAvailable in the system
	public function reserve($eventNum)
	{
		self::$events[$eventNum]--;
	}
	
	//not really random
	//gives the lazies the least popular event
	private function selectRandom($slot)
	{
		$best_event = 1;
		$best_ratio = 0;
		$eventsQuery = "SELECT * FROM `testschema`";
		$all_events = mysql_query($eventsQuery);
		$rowID = 1;
		while ($row = mysql_fetch_assoc($all_events)) {
			if($row["slotsAvailable"] > 0 && $row["length"] == 50)
			{
				$ratio = self::$events[$rowID]/$row["capacity"];
				if($ratio > $best_ratio)
				{
					$best_ratio = $ratio;
					$best_event = $rowID;
				}
			}
			$rowID++;
		}
		return $best_event;
	}
	
    //the users events have now been logged in the database, now the users events have to be freed from the table
    //the system count doesn't have to be changed because those slots haven't been freed so they should stay decremented.
    //each user will have an instance of the system it is a part of so it can handle freeing and reserving its current events
	public function register($user)
	{
		$userEvents = $user->GetEvents();
		for($j = 0; $j < 4; $j++)
		{
            //random
			if($userEvents[$j] === 0 || !isset($userEvents[$j]))
			{
				$userEvents[$j] = self::selectRandom($j);
			}
            
            //evemt full
			if($userEvents[$j] != -1 && self::$events[$userEvents[$j]] < 1)
			{
                $sql = "SELECT * from `testschema` WHERE id=".$userEvents[$j];
                if(!($result = mysql_query($sql)))
                   // header("Location: login.php?error=2");
                while($row = mysql_fetch_array($result));
                    $evname = $row['name'];
				header("Location: registration.php?error=3&event=".$evname);
			}

            //if the event id = -1 then that was disabled for a long event in the timeslot before
            if($userEvents[$j] != -1)
            {
                //Update available slots
                $i = $userEvents[$j];
                $countQuery="SELECT * FROM `testschema` WHERE id='".$i."'";
                $result = mysql_query($countQuery);
                $temp = mysql_fetch_assoc($result);
                $slots = $temp["slotsAvailable"] - 1;
                $eventCount[$j] = $slots;
                $updateQuery = "UPDATE `testschema` SET slotsAvailable=".$eventCount[$j]." WHERE id=".$i;
                $check = mysql_query($updateQuery);
                if(!$check)
                    header("Location: login.php?error=2");
                //Update attendees
                $current = "SELECT * FROM `testschema` WHERE id=".$i;
                if(!($currentTable = mysql_query($current)))
                   header("Location: login.php?error=2");
                $currentRow = mysql_fetch_assoc($currentTable);
                if(strlen($currentRow['attendees']) > 1)
                    $names = $currentRow['attendees']."|".$user->GetID();
                else
                    $names = $user->GetID();
                $nameAdd = "UPDATE `testschema` SET attendees='".$names."' WHERE id=".$i;
                if(!(mysql_query($nameAdd)))
                    header("Location: login.php?error=2");
            }
		}
        //put user into people database
		$person_check = "SELECT * FROM people WHERE studentID=".$user->GetID();
		$userUpdate = "";
		$name_get = "SELECT * FROM studentdata WHERE BCPStudID=".$user->GetID();
		if(!($nameList = mysql_query($name_get)))
            header("Location: login.php?error=2");
		$username = mysql_fetch_assoc($nameList);
		$userUpdate = "INSERT INTO people VALUES (".$user->GetID().",'".$username["StudLastFirst"]."', ".$userEvents[0].", ".$userEvents[1].",".$userEvents[2].",".$userEvents[3].")";
		if(!(mysql_query($userUpdate)))
            header("Location: login.php?error=2");

        return true;
	}
	
	//when called returns all the users attending a certain event in the form lastname, first
	public function displayByEvent($event)
	{
		$participantQuery = "SELECT * FROM `testschema` WHERE id=".$event;
		$participantList = mysql_query($participantQuery);
		$participantArray = mysql_fetch_assoc($participantList);
		$participants = $participantArray["attendees"];
		$participants_exploded = explode("|", $participants);
		$attendees = "";
		for($i = 0; $i < count($participants_exploded); $i++)
		{
			$id_query = "SELECT * FROM studentdata WHERE BCPStudID=".$participants_exploded[$i];
			$id_one = mysql_query($id_query);
			if(!$id_one)
                ;
			else
				$id_two = mysql_fetch_assoc($id_one);
			$name = $id_two["StudLastFirst"];
			if(strlen($attendees) < 1)
				$attendees = $name;
			else
				$attendees = $attendees."|".$name;
		}
		return $attendees;//names seperated by |
	}
	
	//returns all the events a certain user is going to based upon id
	//could be used for friends/teachers/mailing lists
	public function displayByID($id)
	{
		$eventQuery = "SELECT * FROM people WHERE studentID=".$id;
        $event_list = mysql_query($eventQuery);
		if(!($event_list))
            return FALSE;
		$event_array = mysql_fetch_assoc($event_list);
		$eventString = $event_array['event1']."|".$event_array['event2']."|".$event_array['event3']."|".$event_array['event4'];
		return $eventString;
	}
	
	//similar to above, returns based on name. Has to exactly match the database
	public function displayByFullName($name)
	{
		$nameList = explode(" ", $name);
		//echo("0: ".$nameList[0]." 1: ".$nameList[1]);
		$name_query = "SELECT * FROM studentdata WHERE StudFirstName='".$nameList[0]."', StudLastName='".$nameList[1]."'";
		$name_rows = mysql_query($name_query);
		if(!$name_rows)
			header("Location: login.php?error=2");
		$result = "";
		while ($row = mysql_fetch_assoc($name_rows)) {
			$temp = displayByID($row["BCPStudID"]);
			if(strlen($result) < 1)
				$result = $temp;
			else
				$result = $result.", ".$temp; 
		}
		return $result;
	}
	
	//gets the emails of everyone attending an event
	//could be put in some sort of admin page or something so all the emails of people attending an event can be gotten
	public function eventEmails($id)
	{
		$participantQuery = "SELECT * FROM `testschema` WHERE id=".$event;
		$participantList = mysql_query($participantQuery);
		if(!$participantList)
		{}
		else
			$participantArray = mysql_fetch_assoc($participantList);
		$participants = $participantArray["attendees"];
		$participants_exploded = explode(",", $participants);
		$attendees;
		for($i = 0; $i < count($participants_exploded); $i++)
		{
			$id_query = "SELECT * FROM studentdata WHERE BCPStudID=".$participants_exploded[$i];
			$id_one = mysql_query($id_query);
			$id_two = mysql_fetch_assoc($id_one);
			$name = $id_two["StudentEmail"];
			$attendees[$i] = $name."|";
		}
		return $attendees;//names seperated by |
	}



    //only reserves slot from system
    public function selectFromSystem($id)
    {
        if($id>0)
            {
                system::reserve($id);
                echo "reserved.";
            }
    }

    public function addUser($user, $id)
    {
        if($users == null)
            $users[$id] = $user;
        else if(!in_array($user, $users))
            $users[$id] = $user;
    }

    public function getUserId($user)
    {

    }

    public function getUser($id)
    {
        return $user[$id];
    }

}

//Class for user created dynamically as multiple people sign in
//References same system object
class User
{

	private $myEvents;
	private $studentID = 0;

    //Checks registration
	public function __construct($username)
	{
        
		$email = $username;
		if(strcmp(substr($email, -8), "@bcp.org") != 0)
		{
			$email = $email."@bcp.org";
		}
		$idQuery = "SELECT * FROM studentdata WHERE StudentEmail='".$email."'";
		$idList = mysql_query($idQuery);
		if(!$idList)
			header("Location: login.php?error=2");
		$idArray = mysql_fetch_assoc($idList);

        //check to see if they registered already
		$this->studentID = $idArray["BCPStudID"];
		$check_registration = "SELECT * FROM people WHERE studentID='".$this->studentID."'";
		$result = mysql_query($check_registration);
		if(!$result)
		{}
		else
			while($row = mysql_fetch_assoc($result))
			{
				if($this->studentID === $row['studentID'])
                    header("Location: login.php?error=1");
			}
	}
	
    //Selects event from system
	public function select($id, $timeSlot)
	{
        $this->deselect($this->myEvents[$timeSlot], $timeSlot);
		$this->myEvents[$timeSlot] = $id;
        if($id > 0)
            system::reserve($id);
        else
            header("Location: login.php?error=3");
	}

	
    //deselects event from system
	private function deselect($id, $timeSlot)
	{
		$this->myEvents[$timeSlot] = 0;//0 will select randomly
		system::free($id);
	}
	
    //Gets studentID of user
	public function GetID()
	{
		return $this->studentID;
	}
	
    //Gets events of user
	public function GetEvents()
	{
		return $this->myEvents;
	}
	
}

?>
