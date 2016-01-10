<?php 
require 'routes.php';
require 'controllers.php';
//print_r($ROUTE);
//print $_SERVER['REQUEST_METHOD'];

if(isset($_SERVER['HTTP_X_TOKEN']))
{$token=$_SERVER['HTTP_X_TOKEN'];
}
else
{	$token=0;}
//print $token;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if($ROUTE[$_POST['function']]['method']==='POST')
		{
		
		if($ROUTE[$_POST['function']]['url']==='deleteProduct')
		{
		$cont=new controllers();
		$cont->deleteProduct($_POST,$token);
		}
		else if($ROUTE[$_POST['function']]['url']==='addProduct')
		{
		$cont=new controllers();
		$cont->addProduct($_POST,$token);
		}
		else if($ROUTE[$_POST['function']]['url']==='searchProduct')
		{
		$cont=new controllers();
		$cont->searchProduct($_POST,$token);
		}
		else if($ROUTE[$_POST['function']]['url']==='registration')
		{
		$cont=new controllers();
		$cont->registration($_POST);
		}
		else if($ROUTE[$_POST['function']]['url']==='login')
		{
		$cont=new controllers();
		$cont->login($_POST);
		}
		else if($ROUTE[$_POST['function']]['url']==='logout')
		{
		$cont=new controllers();
		$cont->logout($token);
		}
		else if($ROUTE[$_POST['function']]['url']==='viewAll')
		{
		$cont=new controllers();
		$cont->viewAll($token);
		}
		else if($ROUTE[$_POST['function']]['url']==='updateProduct')
		{
		$cont=new controllers();
		$cont->updateProduct($_POST,$token);
		}
		else
		{
		$ret['message']="No such function";
		$ret['status_code']=404;
		print json_encode($ret);
		
		}
		
		}
	else
		{
		$ret['message']="Wrong Method POST";
		$ret['status_code']=404;
		print json_encode($ret);
		
		}
}
else{
	if(isset($_GET['function']))
	{
		if($ROUTE[$_GET['function']]['method2']==='GET')
		{
			if($ROUTE[$_GET['function']]['url2']==='searchProduct' and (isset($_GET["product_name"]) or isset($_GET["product_category"])))
			{
				$cont=new controllers();
				//print $_GET["product_category"].$_GET["product_name"];
				if(isset($_GET["product_name"]))
					$product_name=$_GET["product_name"];
				else
					$product_name="null";
				
				if(isset($_GET["product_category"]))
					$product_category=$_GET["product_category"];
				else
					$product_category="null";
				
				$cont->searchProductByGET($product_name,$product_category);
			}
			else if($_GET['function']=="searchProduct")
			{
				$ret['message']="Undefined Product name or category";
				$ret['status_code']=404;
				print json_encode($ret);
			}
			else if($_GET['function']=="viewAll")
			{
				$cont= new controllers();
				$cont->viewAllByGET();
			}
			else{
				$ret['message']="Undefined function";
				$ret['status_code']=404;
				print json_encode($ret);
			}
		}
		else
		{
			$ret['message']="Wrong Method GET";
			$ret['status_code']=404;
			print json_encode($ret);
		
		}
	}
	else
		{
		$ret['message']="Wrong Method";
		$ret['status_code']=404;
		print json_encode($ret);
		}
	}


?>
