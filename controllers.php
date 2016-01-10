<?php 
include 'authorization.php';
class controllers{
//addProduct Function=============== 
	public function addProduct($a,$token)
	{
		
	$obj = new auth();
	if($obj->can_access($token,'addProduct'))	
		{
		$product_name=$a['product_name'];
		$product_price=$a['product_price'];
		$product_category=$a['product_category'];
		$product_sd=$a['product_sd'];
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				
			$statement = $cursor->prepare("INSERT INTO product(product_name, product_category, product_price,product_sd)
			VALUES(:p_name, :p_category, :p_price, :p_sd)");
			$statement->execute(array(
			"p_name" => $product_name,
			"p_category" => $product_category,
			"p_price" => $product_price,
			"p_sd" => $product_sd 
			));	
				
			$cursor=null;
			$ret['message']="ADDED";
			$ret['status_code']=200;
			$ret['success']=True;
			print json_encode($ret);
			}
		}
	else
		{
		$ret['message']="Auth ERROR";
		$ret['status_code']=101;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
//===============	
//deleteProduct Function=============== 
	public function deleteProduct($a,$token)
	{
		
	$obj = new auth();
	if($obj->can_access($token,'deleteProduct'))	
		{
		$product_id=$a['product_id'];
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
			$statement = $cursor->prepare("DELETE FROM product WHERE product_id=?");
			$statement->execute(array($product_id));		
			$cursor=null;
			$ret['message']="DELETED";
			$ret['status_code']=200;
			$ret['success']=True;
			print json_encode($ret);
			}
		}
	else
		{
		$ret['message']="Auth ERROR";
		$ret['status_code']=101;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
//===================


//searchProduct Function=============== 

	public function searchProduct($a,$token)
	{
		
	$obj = new auth();
	if($obj->can_access($token,'searchProduct'))	
		{
		$flag=0;
		if(isset($a['product_name']))	
		$product_name=$a['product_name'];
		else
		{$product_name="null"; $flag=1;}
		if(isset($a['product_category']))
			$product_category=$a['product_category'];
		else
		{$product_category="mobile";$flag+=10;}
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				if($flag!=11)
				{
					$statement = $cursor->prepare("SELECT * from product where product_name LIKE '%".$product_name."%' or product_category LIKE '%".$product_category."%'");
					$statement->execute();
					$data= $statement->fetchAll();
					if(isset($data[0]))
						print_r(json_encode($data));
					else
					{return 0;}
					$cursor=null;
					$ret['message']="SEARCHED";
					$ret['status_code']=200;
					$ret['success']=True;
					print json_encode($ret);
				}
				else
				{
					if($flag==11)
						$msg="Provide Product name or category";
					
					$ret['message']=$msg;
					$ret['status_code']=102;
					$ret['success']=False;
					print json_encode($ret);					
				}
			}
		}
	else
		{
		$ret['message']="Auth ERROR";
		$ret['status_code']=102;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
//===========================

//updateProduct Function=============== 
	public function updateProduct($a,$token)
	{
		
	$obj = new auth();
	if($obj->can_access($token,'updateProduct'))	
	{
		$product_id=$a['product_id'];
		$product_price=$a['product_price'];
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
		{
			$sql = "UPDATE product SET product_price=".$product_price." WHERE product_id=".$product_id;
			$stmt = $cursor->prepare($sql);
			$stmt->execute();	
			$cursor=null;
			$ret['message']="UPDATED";
			$ret['status_code']=200;
			$ret['success']=True;
			print json_encode($ret);
		}
	}
	else
		{
		$ret['message']="Auth ERROR";
		$ret['status_code']=101;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
//===================

//searchProductByGET Function=============== 

	public function searchProductByGET($product_name,$product_category)
	{	
		
		$flag=0;
		//print $product_name;
		if($product_name=="null")
		{$flag=1;}
		if($product_category=="null")
		{$flag+=10;}
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				if($flag!=11)
				{
					$statement = $cursor->prepare("SELECT * from product where product_name LIKE '%".$product_name."%' or product_category LIKE '%".$product_category."%'");
					$statement->execute();
					$data= $statement->fetchAll();
					if(isset($data[0]))
						print_r(json_encode($data));
					else
					{return 0;}
					$cursor=null;
					$ret['message']="SEARCHED";
					$ret['status_code']=200;
					$ret['success']=True;
					print json_encode($ret);
				}
				else
				{
					if($flag==11)
						$msg="Provide Product name or category";
					
					$ret['message']=$msg;
					$ret['status_code']=102;
					$ret['success']=False;
					print json_encode($ret);					
				}
			}
		
	
	
	}
//===========================

//ViewAll Product  Function=============================

public function viewAll($token)
	{
		
	$obj = new auth();
	if($obj->can_access($token,'searchProduct'))	
		{

		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				$statement = $cursor->prepare("SELECT * from product ");
				$statement->execute();
				$data= $statement->fetchAll();
				if(isset($data[0]))
				print_r(json_encode($data));
				else
				{return 0;}
			}
		}
	else
		{
		$ret['message']="Auth ERROR";
		$ret['status_code']=102;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
//============================

//ViewAllByGET Product  Function=============================

public function viewAllByGET()
	{
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				$statement = $cursor->prepare("SELECT * from product ");
				$statement->execute();
				$data= $statement->fetchAll();
				if(isset($data[0]))
				print_r(json_encode($data));
				else
				{return 0;}
			}
		
	
	}
//============================

//Register User Function===================================================

public function registration($a)
	{
		$flag=0;
		if(isset($a['username']))
		$username=$a['username'];
		else
		$flag=$flag+1;	
	
	
	
	
		if(isset($a['password']))
		$password=$a['password'];
		else
		$flag=$flag+10;	
	
	
	
	
		if(isset($a['name']))
		$name=$a['name'];
		else
		$flag=$flag+100;	
		
		
		if(isset($a['mobile']))
		$mobile=$a['mobile'];
		else
		$flag=$flag+1000;	
	
	
	
		if(isset($a['address']))
		$address=$a['address'];
		else
		$address='notset';	
	
		if($flag==0)
		{
	
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				
			$statement = $cursor->prepare("INSERT INTO users(username, password, name,mobile, address, created_on)
			VALUES(:username, :password, :name, :mobile, :address, now())");
			try{
			$statement->execute(array(
			"username" => $username,
			"password" => $password,
			"name" => $name,
			"mobile" => $mobile , 
			"address" => $address
			));	
			}
			catch(PDOException $err) 
			{
			if($err->getMessage()=="SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'afjal' for key 'PRIMARY'"){
			$ret['message']="DUPLICATE USER NAME";
			$ret['status_code']=200;
			$ret['success']=FALSE;
			print json_encode($ret);
			
			}
			}
			$cursor=null;
			$ret['message']="ADDED";
			$ret['status_code']=200;
			$ret['success']=True;
			print json_encode($ret);
			}
		}
		else
		{
			
		if(flag==1)
			$msg="username missing";
		else if(flag==10)
			$msg="password missing";
		else if(flag==11)
			$msg="username and password missing";
		else if(flag==100)
			$msg="name missing";
		else if(flag==110)
			$msg="name and password missing";
		else if(flag==111)
			$msg="username name and password missing";
		else if($flag==1000)
			$msg="mobile missing";
		else if($flag==1010)
			$msg="password and mobile missing";
		else if ($flag==1001)
			$msg="username and mobile missing";
		else if($flag==1100)
			$msg="name and mobile missing";
		else if($flag==1110)
			$msg="name , password and mobile missing";
		else if($flag==1111)
			$msg= "username, name , password and mobile missing";
		$ret['message']=$msg;
		$ret['status_code']=102;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
	//===========================

	//Login User Function===================================================
public function login($a)
	{
		$flag=0;
		if(isset($a['username']))
		$username=$a['username'];
		else
		$flag=$flag+1;	
	
	
	
	
		if(isset($a['password']))
		$password=$a['password'];
		else
		$flag=$flag+10;	
	
	
	
	
		if(isset($a['device_id']))
		$device_id=$a['device_id'];
		else
		$flag=$flag+100;	
		
		
		if($flag==0)
		{
	
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
			$statement = $cursor->prepare("SELECT COUNT(*) from users where username= ? and password=?");
			$statement->execute(array($username,$password));
			$count= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
			try{
			if($count[0]==1)
			{
				$token = md5(uniqid($username.time(), true));
				$statement = $cursor->prepare("SELECT * from users where username= ? and password=?");
				$statement->execute(array($username,$password));
				$count1= $statement->fetchAll(PDO::FETCH_COLUMN, 0);
				$user_id=$count1[0];
				//print $user_id;
				$statement2 = $cursor->prepare("DELETE FROM tokens WHERE user_id=? and device_id=?");
				$statement2->execute(array($user_id, $device_id));
				$statement = $cursor->prepare("INSERT INTO tokens(user_id, token, device_id)
				VALUES(:user_id, :token, :device_id)");
				try{
				$statement->execute(array(
				"user_id" => $user_id,
				"token" => $token,
				"device_id" => $device_id
				));	
				$ret['token']=$token;
				$ret['Username']=$username;
				$ret['success']=True;
				print json_encode($ret);
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
					return 0;
				}
			}
			else
			{	
				$ret['message']="Invalid Username and password";
				$ret['status_code']=100;
				$ret['success']=False;
				print json_encode($ret);
			}
			}
			catch( Exception $e)
			{return 0;}
				}
		}
		else
		{
			
		if($flag==1)
			$msg="username missing";
		else if($flag==10)
			$msg="password missing";
		else if($flag==11)
			$msg="username and password missing";
		else if($flag==100)
			$msg="device id missing";
		else if($flag==110)
			$msg="d and password missing";
		else if($flag==111)
			$msg="username,  device id and password missing";
		$ret['message']=$msg;
		$ret['status_code']=102;
		$ret['success']=False;
		print json_encode($ret);
		}
	
	}
	//==================================
	
//Logout User Function===================================================
public function logout($a)
{
	
	
		$cursor=get_conn();
		if($cursor==False)
			{
			$ret['message']="Error connecting to database";
			$ret['status_code']=100;
			$ret['success']=False;
			print json_encode($ret);
			}
		else
			{
				try{
				$statement2 = $cursor->prepare("DELETE FROM tokens WHERE token=?");
				$statement2->execute(array($a));
				$ret['message']="Logged out Succesful";
				$ret['status_code']=200;
				$ret['success']=True;
				print json_encode($ret);
				}
				catch(Exception $e)
				{
					$ret['message']="Invalid token";
					$ret['status_code']=100;
					$ret['success']=False;
					print json_encode($ret);
				}
			}
		
		
}
}
?>
