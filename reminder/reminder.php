<?php


namespace App\Main\Plugin\reminder;

use App\Main\Model\authModel;
use App\Main\Model\plugin;
use App\Main\Plugin\reminder\Controller\helper;
use App\Main\Plugin\reminder\Model\reminder;

use App\Main\Plugin\reminder\Model\PHPMailer;


plugin::info([
    "title" => "Reminder",
    "slug" => plugin::get_name(__DIR__)
]);


if(isset($_SESSION["cms_auth_site"])){


    plugin::checkingSetup("plugin_chat",file_get_contents(__DIR__."/reminder.sql"));


    plugin::add_js([
        "/App/Main/Plugin/reminder/Assets/app.js"
    ]);

    $email   = plugin::get_tmp_storage($_SESSION["cms_auth_site"],"reminder_email");

    Plugin::add_menu([
        [
            "permission"    => "reminder",
            "title"         => "Reminder",
            "url"           => "reminder@dashboard",
            "icon"          => "clock"
        ]
    ]);

    if($email){


        plugin::add_create_form_item("reminder@create","TEXTAREA","description","Description",true,"Enter data...");
        plugin::add_create_form_item("reminder@create","DATE","date","Date",true,"Choose a date...");


        plugin::admin_view_render(
            "Reminder",
            [
                "Reminder"
            ],
            __DIR__,
            "dashboard",
            "dashboard",
            []
        );



        plugin::action("dashboard@widget",function (){

            $getTodayReminderList = reminder::widget_dashboard($_SESSION["cms_auth_site"]);

            plugin::view_render(
                __DIR__,
                "reminderToday",
                [
                    "reminder" => $getTodayReminderList
                ]
            );

        },0);


        plugin::add_router_post("router@admin","/app/plugin/reminder/create",[helper::class,"create"]);
        plugin::add_router_post("router@admin","/app/plugin/reminder/list",[helper::class,"listing"]);
        plugin::add_router_post("router@admin","/app/plugin/reminder/remove",[helper::class,"remove"]);

    }else{

        plugin::admin_view_render(
            "Reminder",
            [
                "Reminder",
                "Install"
            ],
            __DIR__,
            "dashboard",
            "setup",
            []
        );

        plugin::add_router_post("router@admin","/app/plugin/reminder/setup",[helper::class,"setup"]);

    }

}else{

    plugin::hook()->add_action("system@cron",function (){

        foreach (reminder::getListAllReminder() as $item) {

            if(date("Y-m-d") === date("Y-m-d",$item["dates"]) ){

                if( plugin::get_tmp_storage($item["siteCode"],"reminder_email") ){

                    $getEmail = plugin::get_tmp_storage($item["siteCode"],"reminder_email");

                    if($getEmail){

                        $mail = new PHPMailer();

                        $mail->IsSMTP();
                        $mail->SMTPDebug = 1;
                        $mail->SMTPAuth = __SMTP_PORT__;
                        $mail->SMTPSecure = __SMTP_PORT__ ? 'ssl' : 'tls';
                        $mail->Host = __SMTP_HOST__;
                        $mail->Port = __SMTP_PORT__;
                        $mail->Username = __SMTP_MAIL__;
                        $mail->Password = __SMTP_PASSWORD__;
                        $mail->SetFrom($mail->Username, __SMTP_SENDER__);
                        $mail->AddAddress($getEmail, authModel::getSite($item["siteCode"])["title"]);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = 'Reminder '.date("m.d.Y");
                        $mail->MsgHTML($item["description"]);
                        $mail->Send();

                    }

                }

            }

        }

    });

}
