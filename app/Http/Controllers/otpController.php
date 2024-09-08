<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
//use Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class otpController extends Controller
{
    public function loginWithOtpPost(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email'=>'required|email|max:200'
        ]);

        $checkUser=user::where('email',$request->email)->first();
        if(is_null($checkUser)){
            return redirect()->back()->with('error','Your account is not registered with Us');
        }else{
            $otp=rand(100000,999999);
            $now=now();
            $updateUser=User::where('email',$request->email)->update([
                'otp'=>$otp,
                'expire_at'=>$now
            ]);

            //send emailwith otp valid upto 10min 
            Mail::send('emails.loginWithOtpEmail', ['otp'=>$otp], function ($message ) use($request) {
                $message->to($request->email);
                $message->subject('Login with OTP-Check mree');
                
                
            });
            return redirect()->route('confirm.login.with.otp')->with('success','check your email for get OTP');
        }
    }

    public function confirmloginWithOtpPost(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email'=>'required|email|max:200',
            'otp'=>'required'
        ]);

        $checkUser=user::where('email',$request->email)->where('otp',$request->otp)->first();
        if(is_null($checkUser)){
            return redirect()->route('login.with.otp')->with('error','Your  OTP is expired. ');
        }else{
            $expireAtTime=Carbon::createFromFormat('Y-m-d H:i:s',$checkUser->expire_at);
            $currentTime=Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
            $minutDifference=$expireAtTime->diffInMinutes( $currentTime);
            if($minutDifference <=1){
                $updateUser=User::where('email',$request->email)->update([
                'otp'=>null,
                'expire_at'=>null
            ]);
            Auth::login($checkUser);
            return redirect('home');

            }



            
                   
            return redirect()->route('login.with.otp')->with('error','Your email or OTP is expired. ');
        }
    }
}
