README.txt

Post Push Service Hook to deploy code.

Overview:

Perform 'git pull' on remote version of a repository through a php script.

Installation:

Copy github.php and github.config.php to the base of your checked out code on your server.  You then will need to setup a service hook on your github repo.  (Admin -> Service Hooks -> Post-Receive URLs)  Point the hook to the location of github.php (http://www.yourdomain.com/github.php)

Configuration:

github.config.php:

enabled -> Kill switch (on == deploy)
to_email ->  Email Address to send deployment notifications to.
from_email -> Email Address used as from (deploy@yourdomain.com)
site_name -> Name for your site
limit_users -> flag to only allow whitelisted users to deploy
valid_users -> array of valid github usernames that are allowed to deploy from push.
