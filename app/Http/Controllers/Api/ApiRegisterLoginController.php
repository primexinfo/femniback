<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Otp;
use App\Models\User;
use App\Models\ApptTime;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;
use DB;
use JWTAuth;

class ApiRegisterLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            ['login','set_appoint','registerOtp','resendOtp','register','forgetPasswordOtp']
        ]);
    }

    public function set_appoint(Request $request){
        
        $this->validate($request, [
            'appointment_name' => 'required',
            'phone' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
        ]);
        $appt_time = new ApptTime();
        $appt_time->doctor_id = $request->doctor_id;
        $appt_time->name = $request->appointment_name;
        $appt_time->phone = $request->phone;
        $appt_time->dob = $request->dob;
        $appt_time->sex = $request->sex;
        $appt_time->marital_status = $request->marital_status;
        $appt_time->address = $request->address;
        $appt_time->blood_group = $request->blood_group;
        $appt_time->dates = $request->appointment_date;
        $appt_time->times = $request->appointment_time;
        $appt_time->status = 0;
        $appt_time->save();

        return response()->json($message = 'Appointent Create successful!');

    }
    //Registration with otp
    public function registerOtp(Request $request)
    {
        //--- Validation Section
        $rules = [
            'phone' => 'required|unique:users',
            'password' => 'required|confirmed'
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        //--- OTP Section
        $otp = mt_rand(1000, 9999);
        $phone = $request->phone;
        try {
            $smsUrl = "http://gosms.xyz/api/v1/sendSms?username=medylife&password=Vu3wq8e7j7KqqQN&number=(" . $phone . ")&sms_content=Your%20OTP%20is:%20" . $otp . "&sms_type=1&masking=non-masking";

            //otp table

            $otp_code = new Otp();
            $otp_code->name = $request->name;
            $otp_code->password = $request->password;
            $otp_code->email = $request->email;
            $otp_code->phone = $request->phone;
            $otp_code->otp = $otp;
            $otp_code->save();

            //--- Send api sms request

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $smsUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_exec($curl); //response output
            curl_close($curl);

            return response()->json(['status' => 'success','message'=>'Otp sent successfully'], 200);

        } catch (\Exception $exception) {

            return response()->json($exception->getMessage());

        }
    }

    //Registration  otp resend
    public function resendOtp(Request $request){

        //--- Validation Section
        $rules = [
            'phone' => 'required|unique:users',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $registration_info = Otp::where('phone',$request->phone)->latest()->first();

        if($registration_info){
            //--- OTP Section
            $otp = mt_rand(1000, 9999);

            $phone = $request->phone;
            try {
                $smsUrl = "http://gosms.xyz/api/v1/sendSms?username=medylife&password=Vu3wq8e7j7KqqQN&number=(" . $phone . ")&sms_content=Your%20OTP%20is:%20" . $otp . "&sms_type=1&masking=non-masking";

                //--- Send api sms request

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $smsUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, false);
                curl_exec($curl); //response output
                curl_close($curl);

                //otp table update

                $registration_info->update([
                    'otp' => $otp,
                ]);

                return response()->json(['status' => 'success','message'=>'Otp resend successfully'], 200);
            }
            catch (\Exception $exception){
                return response()->json($exception->getMessage());
            }
        }
        else{
            return response()->json(['status' => 'warning','message'=>'User not found'], 200);
        }

    }

    //Registration  store
    public function register(Request $request){

        //--- Validation Section
        $rules = [
            'phone' => 'required|unique:users',
            'otp' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $registration_info = Otp::where('phone',$request->phone)
            ->where('otp',$request->otp)->latest()->first();

        try{
            if($registration_info){

                //Registration
                $password = $registration_info->password;
                $user = new User();
                $user -> name = $registration_info->name;
                $user -> email = $registration_info->email;
                $user -> phone = $registration_info->phone;
                $user -> password = bcrypt($password);
                $token = md5(time().$registration_info->name.$registration_info->email);
                $user -> verification_link = $token;
                $user -> affilate_code = md5($registration_info->name.$registration_info->email);;
                $user->save();

                //delete Otp data
                $delete_datas = Otp::where('phone',$registration_info->phone)->get();
                foreach($delete_datas as $delete_data){
                    $delete_data->delete();
                }

                //Notification
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->save();

                Auth::guard('web')->login($user);

                $token = JWTAuth::fromUser($user);

                return response()->json(compact('user','token'),201);
            }
            else{
                return response()->json(['status' => 'error','message'=>'Registration failed'], 401);
            }
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }

    public function login(Request $request)
    {

        $credentials = $request->only('phone', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
    }


    public function forgetPasswordOtp(Request $request){
        //--- Validation Section
        $rules = [
            'phone' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $phone = $request->phone;
        $otp = mt_rand(1000, 9999);
        $user = User::where('phone',$phone)->first();
        if($user){
            try {
                $smsUrl = "http://gosms.xyz/api/v1/sendSms?username=medylife&password=Vu3wq8e7j7KqqQN&number=(" . $phone . ")&sms_content=Your%20OTP%20is:%20" . $otp . "&sms_type=1&masking=non-masking";

                //otp table

                $otp_code = new Otp();
                $otp_code->phone = $request->phone;
                $otp_code->otp = $otp;
                $otp_code->save();

                //--- Send api sms request

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $smsUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, false);
                curl_exec($curl); //response output
                curl_close($curl);

                //otp table update

                return response()->json(['status' => 'success','message'=>'Otp resend successfully'], 200);
            }
            catch (\Exception $exception){
                return response()->json($exception->getMessage());
            }
        }
        else{
            return response()->json(['status' => 'Error','message'=>'User not found'], 200);
        }

    }

    public function forgetPasswordcheckOtp(Request $request){
        //--- Validation Section
        $rules = [
            'phone' => 'required',
            'otp' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $otp = Otp::where('phone',$request->phone)->latest()->first();
        if($otp->otp == $request->otp){
            return response()->json(['status' => 'Success','message'=>'Otp match successfully'], 200);
        }
        else{
            return response()->json(['status' => 'Error','message'=>'Otp has not match'], 200);
        }


    }

    public function resetPassword(Request $request){
        //--- Validation Section
        $rules = [
            'phone' => 'required',
            'password' => 'required|confirmed'
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try{

            //update password
            $user = User::where('phone',$request->phone)->first();
            $user -> password = bcrypt($request->password);
            $user->update();

            //delete Otp data
            $otps = Otp::where('phone',$request->phone)->get();
            foreach($otps as $delete_data){
                $delete_data->delete();
            }

            Auth::guard('web')->login($user);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }



    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try{
            return response()->json($this->guard()->user());
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }


}