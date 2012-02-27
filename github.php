<?php
include('github.config.php');

function myException($exception)
{
	$body=$exception->getMessage();
	$subject="Deployment ERROR";
	$headers = "From: ".$from_email."\n";
	mail($to_email,$subject,$body,$headers);
}

set_exception_handler('myException');

//$reason = "START";

if ($enabled)
{
	$data = json_decode($_POST[payload], true);
	$pusher = $data[pusher][name];
	$pusher_email = $data[pusher][email];

	//$reason = " -- ENABLED";
	$safe_to_deploy = true;

	if ($limit_users)
	{
		$safe_to_deploy = false;
		//$reason .= " -- Limit Users";
		if(in_array($pusher, $valid_users))
		{
			$safe_to_deploy = true;
			//$reason .= " -- VALID";
		}
	}
	else
	{
		$safe_to_deploy = false;
		//$reason .= " -- No Limit Users";
		if (isset($pusher) && isset($pusher_email))
		{
			//$reason .= " -- PUSHER OK";
			$safe_to_deploy = true;
		}
	}

	if ($safe_to_deploy)
	{
		//$reason .= " -- SAFE TO DEPLOY";
		$repo = $data[repository][name];
		$repo_url = $data[repository][url];
		//`git pull`;
		
		$body="Site: ".$site_name."\n Pusher: ".$pusher."\n Pusher Email: ".$pusher_email."\n Repo: ".$repo."\n Repo URL: ".$repo_url."\n";
		$subject="Deployment - ".$site_name;
		$headers = "From: ".$from_email."\n";
	}
	else
	{
		//$reason .= " -- FAIL TO DEPLOY";
		$body="Site: ".$site_name."\n IP: ".$_SERVER['REMOTE_ADDR']."\n\n\n".serialize($_REQUEST);
		$subject="Deployment Failure - ".$site_name;
		$headers = "From: ".$from_email."\n";
	}
}
else 
{
	//$reason = " -- DISABLED";
	$body="Site: ".$site_name."\n IP: ".$_SERVER['REMOTE_ADDR']."\n\n\n".serialize($_REQUEST);
	$subject="Deployment Disabled - ".$site_name;
	$headers = "From: ".$from_email."\n";

}

	//mail($to_email,$subject,$body."\n --- \n".$reason,$headers);
	mail($to_email,$subject,$body,$headers);

?>
