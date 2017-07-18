<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Command
 *
 * @author Xianshun
 */
require_once('MObj.php');

class Command extends MObj{
    //put your code here
    public function __construct()
    {
        parent::__construct("");
    }

    public static function isAdmin()
    {
        return MObj::hasLoginned() && MObj::hasRole("admin");
    }

    public static function isEditable()
    {
        return true;
    }

    public static function validate()
    {
        $user=Command::getSender();
        $admin=Command::isAdmin();
        if($admin==false && MObj::numUncommittedCommandsToday($user) > 10)
        {
            $content='
            {
                "msg": "Dear '.$user.':\nyou have more than 10 uncommitted request today, \nPlease wait for the requests to be committed"
            }
            ';
            
            return $content;
        }
        return '';
    }

    public static function queue($sQuery, $action, $detail)
    {
        $user=Command::getSender();
        MObj::addCommand($user, $sQuery, $action, $detail);
        $detail=str_replace('<br />', '\n', $detail);
        $content='
        {
            "msg": "Dear '.$user.':\nyour action has been received, we will process your request soon\nAction:\n'.$action.'\nDetail:\n'.$detail.'"
        }
        ';
        echo $content;
    }

    public static function getSender()
    {
        $user=$_SERVER['REMOTE_ADDR'];
        if(MObj::hasLoginned())
        {
            $user=MObj::getUser();
        }

        return $user;
    }
}
?>
