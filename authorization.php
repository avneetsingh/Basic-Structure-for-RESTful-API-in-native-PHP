<?php


function get_conn()
{
$servername = "localhost";
$username = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$servername;dbname=wingify", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
	}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    $conn=False;
	}
return $conn;
}


class auth{
	
	public function get_user_from_username($username)
	{
		$c= get_conn();
		$statement = $c->prepare("SELECT user_id from users where username= ?");
		$statement->execute(array($username));	
		$user_id= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
		return ($user_id[0]);
	}
	
	private function get_user_id_from_token($token)
	{
		$c= get_conn();
		$statement = $c->prepare("SELECT user_id from tokens where token= ?");
		$statement->execute(array($token));	
		$user_id= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
		return ($user_id[0]);
	}
	
	
	private function get_role_user($user_id)
	{
		$c= get_conn();
		$statement = $c->prepare("SELECT role_id from role_user where user_id= ?");
		$statement->execute(array($user_id));	
		$role_id= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
		return ($role_id[0]);
	}

	
	public function can_access($token,$permission)
	{
		//$a->token $b->permission
		$role_id= $this->get_role_user($this->get_user_id_from_token($token));
		$c= get_conn();
		$statement = $c->prepare("SELECT permision_id from permisions where permission= ?");
		$statement->execute(array($permission));	
		$permission_id= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
		
		if(isset($permission_id[0]))
		{$p_id = $permission_id[0];}
		else
		{return 0;}
		
		
		
		$statement = $c->prepare("SELECT count(*) from role_permission where role_id= ? and permission_id=?");
		$statement->execute(array($role_id,$p_id));	
		$count= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
		
		
		try{
		if($count[0]==1)
		{
			return 1;
		}
		else
		{	
			return 0;
		}
		}
		catch( Exception $e)
		{return 0;}
	}
	
}





?>
