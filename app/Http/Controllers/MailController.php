<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Recaptcha;
use Auth;
use DB;
use Mail;
use \App\Mail\SendContactMail;


class MailController extends Controller
{
    public function send_contact(Request $request)
    {
        
        $validatedData = $request->validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required','email'],
            'phone' => ['nullable', 'max:13'],
            'captcha_token' => [new Recaptcha],
        ]);
       $input = json_decode($request->getContent(), true);
        Mail::to(env('MAIL_TO'))->send(
            new SendContactMail(
                $input,
                'New Contact Added'
            )
        );
        return ['status'=>'success','message'=>'Your contact information sent to admin successfully.'];
    }
}
