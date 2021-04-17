<?php
/**
 * Created by PhpStorm.
 * User: cengizakcan
 * Date: 4.05.2020
 * Time: 02:17
 */

namespace App\Main\Plugin\reminder\Controller;

use App\Main\Model\plugin;
use Fix\Support\Header;
use App\Main\Plugin\reminder\Model\reminder;

class helper {

    public function __construct(){

        // return $this->__construct();


    }


    public static function setup(){

        try{

            Header::checkParameter($_POST,["email"]);
            Header::checkValue($_POST,["email"]);

            if(!filter_var(Header::post("email"), FILTER_VALIDATE_EMAIL))
                throw new \Exception("Email is incorrect");

            if(!plugin::get_tmp_storage($_SESSION["cms_auth_site"],"reminder_email")){
                plugin::add_tmp_storage($_SESSION["cms_auth_site"],"reminder_email",Header::post("email"));
            }

            Header::jsonResult("success","SUCCESS", "Installation Complete");

        }catch (\Exception $exception){

            Header::jsonResult("error","ERROR",$exception->getMessage());
        }

    }


    public static function create(){

        try{

            Header::checkParameter($_POST,["description","date"]);
            Header::checkValue($_POST,["description","date"]);

            if(!reminder::create_reminder(self::uuid(),$_SESSION["cms_auth_site"],strtotime(Header::post("date")),Header::post("description")))
                throw new \Exception("Reminder failed");

            Header::jsonResult("success","SUCCESS", "Reminder added");

        }catch (\Exception $exception){

            Header::jsonResult("error","ERROR",$exception->getMessage());
        }

    }
    public static function remove(){

        try{

            Header::checkParameter($_POST,["uuid"]);
            Header::checkValue($_POST,["uuid"]);

            if(!reminder::remove_reminder($_SESSION["cms_auth_site"],Header::post("uuid")))
                throw new \Exception("The reminder could not be deleted");

            Header::jsonResult("success","SUCCESS", "Reminder removed");

        }catch (\Exception $exception){

            Header::jsonResult("error","ERROR",$exception->getMessage());
        }

    }



    public static function listing() {

        $export = [];

        foreach (reminder::getList($_SESSION["cms_auth_site"]) as $item) {

            $export[] = [
                $item["uuid"],
                $item["description"],
                date("m.d.Y",$item["dates"]),
                reminder::checkReturn(intval($item["dates"])),
                date("m.d.Y",$item["time"])
            ];

        }

        Header::jsonResult("success","SUCCESS", "Data attached ...",$export);


    }

    public static function uuid(){
        return rand(1111,9999)."-".rand(1234,9876)."-".rand(2343,7798)."-".rand(1122,7788);
    }



}