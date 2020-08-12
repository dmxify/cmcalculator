<?php

require(__DIR__.'/../config.php');
require(__DIR__.'/../db.php'); // creates database connection $mysqli

$resources = array();
$resources['users_total-count'] = ['query'=>'SELECT COUNT(*) AS `count` FROM `user`','user_roles' => ['admin']];
// $resources['users_new-this-week'] = ['is_stored_procedure' => true,'query'=>'CALL `users_new-this-week`();','user_roles' => ['admin']];
// $resources['users_new-last-week'] = ['is_stored_procedure' => true,'query'=>'CALL `users_new-last-week`();','user_roles' => ['admin']];
$resources['users_new-this-week'] = ['query'=>'SELECT `days`, SUM(`count`) as `count` FROM ( SELECT `D`.`days`, `D`.`count` FROM `days` `D` WHERE `D`.`days` < 7 UNION ALL SELECT DATEDIFF(NOW(),`date_created`) AS `days`, COUNT(*) as `count` FROM `user` WHERE `date_created` > NOW() - INTERVAL 6 DAY GROUP BY DATEDIFF(NOW(),`date_created`) ) derived GROUP BY `days`','user_roles' => ['admin']];
$resources['users_new-last-week'] = ['query'=>'SELECT `days`, SUM(`count`) as `count` FROM( SELECT `D`.`days`, `D`.`count` FROM `days` `D` WHERE `D`.`days` < 7 UNION ALL SELECT DATEDIFF(NOW(),`date_created`)-7 AS `days`, COUNT(*) as `count` FROM `user` WHERE `date_created` < NOW() - INTERVAL 6 DAY AND `date_created` > NOW() - INTERVAL 13 DAY GROUP BY DATEDIFF(NOW(),`date_created`)) derived GROUP BY `days`','user_roles' => ['admin']];
$resources['users_new-daily-per-month'] = ['query'=>'SELECT DAY(`date_created`) AS day, COUNT(*) AS count FROM `user` WHERE MONTH(`date_created`) = MONTH(NOW()) AND YEAR(`date_created`) = YEAR(NOW()) GROUP BY DAY(`date_created`)','user_roles' => ['admin']];
$resources['users_new-weekly-per-year'] = ['query'=> 'SELECT WEEK(`date_created`) AS week, COUNT(*) AS count FROM `user` WHERE YEAR(`date_created`) = YEAR(NOW()) GROUP BY WEEK(`date_created`)', 'user_roles' => ['admin']];
$resources['users_verified-vs-unverified'] = ['query'=>'SELECT `is_email_verified`, COUNT(*) AS `count` FROM `user` GROUP BY `is_email_verified` ORDER BY `is_email_verified` DESC', 'user_roles' => ['admin']];
$resources['users_free-vs-premium'] = ['query'=>'SELECT `is_premium`, COUNT(*) AS `count` FROM `user` GROUP BY `is_premium` ORDER BY `is_premium` DESC', 'user_roles' => ['admin']];
$resources['users_per-country'] = ['user_roles' => ['admin']];
$resources['users_logins-per-day'] = ['user_roles' => ['admin']];
$resources['users_count-vs-time-since-login'] = ['user_roles' => ['admin']];
//COUNT(*) AS 'count',
// gets specific resource's required user roles
function get_allowed_user_roles($resource_name)
{
    global $resources;
    return $resources[$resource_name]['user_roles'];
}

function get_user_roles()
{
    $user_roles = array();
    if (isset($_SESSION) && isset($_SESSION['user'])) {
        if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
            array_push($user_roles, 'admin');
        }
        if (isset($_SESSION['user']['is_premium']) && $_SESSION['user']['is_premium']) {
            array_push($user_roles, 'premium');
        }
    }
    return $user_roles;
}

function user_can_access($resource)
{
    $required_user_roles = get_allowed_user_roles($resource);
    $user_roles = get_user_roles();

    foreach ($user_roles as $role) {
        if (in_array($role, $required_user_roles)) {
            return true;
        }
    }

    return false;
}

function get_resource_query($resource_name)
{
    global $resources;
    return $resources[$resource_name]['query'];
}

function is_stored_procedure($resource_name)
{
    global $resources;
    return isset($resources[$resource_name]['is_stored_procedure'])?$resources[$resource_name]['is_stored_procedure']:false;
}
