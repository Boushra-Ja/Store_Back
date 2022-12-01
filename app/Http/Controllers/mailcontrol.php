<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class mailcontrol extends Controller
{
    public function basic_email()
    {
        $data = array('name' => "Virat Gandhi");

        Mail::send(['text' => 'mail'], $data, function ($message) {
            $message->to('ite2bayan@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
    }

    public static function html_email(String $name,String $code,String $email,String $title)
    {
        $data = array('name' => $name,'code' =>$code);
        Mail::send('mail', $data, function ($message) use ($title, $email) {
            $message->to($email, 'متجر جديد')->subject
            ($title);
            $message->from('itebayan@gmail.com', 'مشروعي');
        });
    }

    public static function invetation(String $name,String $email,String $title)
    {
        $data = array('name' => $name);
        Mail::send('inv', $data, function ($message) use ($title, $email) {
            $message->to($email, 'دعوة لادارة متجر')->subject
            ($title);
            $message->from('itebayan@gmail.com', 'مشروعي');
        });
    }

    public static function accept_store(String $name,String $email,String $title)
    {
        $data = array('name' => $name);
        Mail::send('accept', $data, function ($message) use ($title, $email) {
            $message->to($email, 'متجر جديد')->subject
            ($title);
            $message->from('itebayan@gmail.com', 'مشروعي');
        });
    }


    public static function html_email_password(String $name,String $email,String $title,int $id)
    {
        $data = array('name' => $name,'id' => $id);
        Mail::send('pass', $data, function ($message) use ($title, $email) {
            $message->to($email, 'اعادة تعيين كلمة السر')->subject
            ($title);
            $message->from('itebayan@gmail.com', 'مشروعي');
        });
    }


    public function attachment_email(Request $request)
    {
        $data = array('name' => $request->name,'code' =>$request->code);
        Mail::send('mail', $data, function ($message) use ($request) {
            $message->to($request->email, 'متجر جديد')->subject
            ('التحقق من البريد الالكتوني');
            $message->attach('C:\Users\Bayan\Pictures\Saved Pictures\R.jpg');
            $message->attach('C:\Users\Bayan\Desktop\R.txt');
            $message->from('itebayan@gmail.com', 'مشروعي');
        });
    }

}
