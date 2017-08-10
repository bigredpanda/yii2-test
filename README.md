YII2 TEST APPLICATION
------------
### Requirements
* php >= 5.4.0
* mysql
### Install
* configure your server
* git clone https://github.com/bigredpanda/yii2-test.git to the server web directory
* cd yii2-test
* composer install
* set you database parameters in config/db.php and 
* php yii migrate
* php yii migrate --migrationPath=@yii/rbac/migrations
* php yii rbac/init
* open in browser :)
