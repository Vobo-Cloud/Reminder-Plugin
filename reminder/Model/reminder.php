<?php
/**
 * Created by PhpStorm.
 * User: cengizakcan
 * Date: 4.05.2020
 * Time: 02:17
 */

namespace App\Main\Plugin\reminder\Model;

use App\Main\Model\plugin;
use Fix\Packages\Database\Database;
use Fix\Support\Header;


class reminder {

    public function __construct(){

        // return $this->__construct();


    }

    public static function create_reminder($uuid,$siteCode,$dates,$description){

        return Database::start()->insert("plugin_reminder")->set(["uuid","siteCode","dates","description","ip","time"],[$uuid,$siteCode,$dates,$description,$_SERVER["REMOTE_ADDR"],time()])->run(Database::Progress);

    }


    public static function getList($siteCode){

        return Database::start()->select("plugin_reminder")->where(["siteCode"],[$siteCode])->run(Database::Multiple);

    }

    public static function getListAllReminder(){

        return Database::start()->select("plugin_reminder")->run(Database::Multiple);

    }

    public static function remove_reminder($siteCode,$uuid){

        return Database::start()->delete("plugin_reminder")->where(["uuid","siteCode"],[$uuid,$siteCode])->run(Database::Progress);

    }

    public static function checkReturn($date = null) {

        if(intval(strtotime(date("Y-m-d"))) === strtotime(date("Y-m-d",$date))){
            return "<span class='badge badge-info text-dark'> <i class='fas fa-clock '></i> Today</span>";
        }else if(time() < $date){
            return "<span class='badge badge-success text-dark'> <i class='fas fa-clock '></i> PLANNED</span>";
        }else if(time() > $date){
            return  "<span class='badge badge-primary'>COMPLETED</span>";
        }

    }




    public static function widget_dashboard($siteCode){

        $get = Database::start()->select("plugin_reminder")->where(["siteCode"],[$siteCode])->run(Database::Multiple);

        $export = [];
        foreach ($get as $item)
            if(intval(strtotime(date("Y-m-d"))) === strtotime(date("Y-m-d",$item["dates"])))
                $export[] = $item["description"];

        return $export;
    }

}