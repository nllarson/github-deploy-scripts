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

function validBranch($branchLimit, $ref, $branches) {
        if ($branchLimit) {
                return in_array($ref, $branches);
        } else {
                return true;
        }
}

function validUser($userLimit, $user, $user_email)
{
        if ($userLimit)
        {
                return in_array($user, $valid_users);
        } else {
                return isset($user) && isset($user_email);
        }
}

$data = json_decode($_POST['payload'], true);
$pusher = $data['pusher']['name'];
$pusher_email = $data['pusher']['email'];
$branch_changed = $data['ref'];

if (validBranch($limit_branch, $branch_changed, $valid_branches)) {
        echo "Valid Branch - ".$branch_changed;

        if ($enabled) {
                if (validUser($limit_users, $pusher, $pusher_email)) {
                        echo "Valid User - ".$pusher;

                        $repo = $data['repository']['name'];
                        $repo_url = $data['repository']['url'];

                        if ($git_update)
                        {
                                `git pull`;
                                echo "git pull";
                        }

                        $body="Site: ".$site_name."\n Pusher: ".$pusher."\n Pusher Email: ".$pusher_email."\n Repo: ".$repo."\n Repo URL: ".$repo_url."\n Branch: ".$branch_changed."\n";
                        $subject="Deployment - ".$site_name;
                        $headers = "From: ".$from_email."\n";
                }
                else
                {
                        echo "Not a valid User - Aborting Deployment";
                        $body="Site: ".$site_name."\n IP: ".$_SERVER['REMOTE_ADDR']."\n\n\n".serialize($_REQUEST)."\n\n\n";
                        $subject="Deployment Failure - ".$site_name;
                        $headers = "From: ".$from_email."\n";
                }
        }
        else
        {
                echo "Script Disabled";
                $body="Site: ".$site_name."\n IP: ".$_SERVER['REMOTE_ADDR']."\n\n\n".serialize($_REQUEST);
                $subject="Deployment Disabled - ".$site_name;
                $headers = "From: ".$from_email."\n";
        }

        mail($to_email,$subject,$body,$headers);
        echo "DONE";
} else {
        echo "No Work To Perform - Not Tracking this Branch";
}

?>
