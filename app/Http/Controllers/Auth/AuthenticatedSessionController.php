<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\MobileLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;


class AuthenticatedSessionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        // if (auth()->user()) {
        //     $request->session()->invalidate();
        // }
        // $request->authenticate();

        // $request->session()->regenerate();
        // $token = $request->user("staff")->createToken("user");

        // auth('staff')->user()->clearSMS();
        // return ['token' => $token->plainTextToken];
        // dd($request->user());
        // if (auth()->user()) {
        $request->session()->invalidate();
        // }
        $request->authenticate();

        $request->session()->regenerate();
        $token = $request->user()->createToken("user");
        return ['token' => $token->plainTextToken];

        // return response()->noContent();
    }
    public function storeapi(LoginRequest $request)
    {
        $request->authenticate();

        // Generate a token
        $token = $request->user()->createToken("user");

        // Return the token
        return ['token' => $token->plainTextToken];
        // return response()->noContent();
    }
    public function user(Request $request)
    {

        return  new UserResource(Auth::user());
        // return Auth::user();
    }

    public function create_admin(Request $request)
    {
        // $user=new User();
        try{
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            // 'phone'=>'required',
        ]);

        $user = User::create([
            'name' => "name",
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    } catch (Exception $e) {
        // Exception handling code
        echo 'Caught exception: ' . $e->getMessage();
    }
    // Generate a token for the user
    $token = $user->createToken('user')->plainTextToken;
        // $token = $request->user()->createToken("user");
        return  ['token' => $token];
        // return "ddd";
        // return Auth::user();
    }

    public function updatePushNotificationToken(Request $request)
    {

        $validatedData = $request->validate([
            'token' => 'required|string',

        ]);

        auth()->user("staff")->device_token = $validatedData['token'];
        auth()->user("staff")->save();
        return response(["msg" => "token has been updated"]);




        // $response = $this->authRepository->updatePushNotificationToken($validatedData);


        // return $response;
    }
    public function mobile_verification(Request $request)
    {
        // if (auth()->user()) {
        //     $request->session()->invalidate();
        // }
        $request->validate([
            'mobile' => 'required|exists:staff',
        ]);
        return $this->createToken() ? response()->json(['message' => 'Successfully sent SMS', 'status' => 200]) : response()->json(['message' => 'Server error (SMS)', 'status' => 422], 500);
    }

    public function mobile_verification_admin(Request $request)
    {
        // if (auth()->user()) {
        //     $request->session()->invalidate();
        // }
        $validatedData=  $request->validate([
            'mobile' => 'required',
            'code' => 'required',
        ]);
        return $this->createToken2($validatedData)  ? response()->json(['message' => 'Successfully sent SMS', 'status' => 200]) : response()->json(['message' => 'Server error (SMS)', 'status' => 422], 500);;
    }
    protected function createToken2($validatedData)
    {
        $staff = new Staff();
        $staff->mobile=$validatedData['mobile'];

        $smsStatus = $staff->sendSms($validatedData['code']);
       // $staff->save();
        return $smsStatus;
    }
    public function mobile_login(MobileLoginRequest $request)
    {
        // dd(auth()->user());
        if (auth()->user()) {
            $request->session()->invalidate();
        }
        $request->authenticate();

        $request->session()->regenerate();
        $token = $request->user("staff")->createToken("user");

        auth('staff')->user()->clearSMS();
        return ['token' => $token->plainTextToken];
    }
    public function mobile_login_api(MobileLoginRequest $request)
    {

        $request->validate([
            'mobile' => 'required|exists:staff',
            'verification_code' => 'required|string'
        ]);

        if (!$token =  auth('staff')->attempt(["mobile" => $this->request->mobile, "password" => $this->request->verification_code])) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //if authinticated remove token
        auth('staff')->user()->clearSMS();
        // return token
        // dd($this->request->firebaseMessagingToken);
        //    auth('staff')->user()->updatePushToken($this->request->firebaseMessagingToken,$this->request->firebaseMessagingTokenLang);
        $token = $request->user("staff")->createToken("user");

        // return $this->respondWithToken($token);




        // auth('staff')->user()->clearSMS();
        return ['token' => $token->plainTextToken];
    }
    public function mobile_regester_api(Request $request)
    {

        $request->validate([
            'mobile' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // if (!$token =  auth('staff')->attempt(["mobile" => $this->request->mobile, "password" => $this->request->verification_code])) {

        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        //if authinticated remove token
        // auth('staff')->user()->clearSMS();
        // return token
        // dd($this->request->firebaseMessagingToken);
        //    auth('staff')->user()->updatePushToken($this->request->firebaseMessagingToken,$this->request->firebaseMessagingTokenLang);
        $token = $request->user("staff")->createToken("user");

        // return $this->respondWithToken($token);




        // auth('staff')->user()->clearSMS();
        return ['token' => $token->plainTextToken];
    }
    protected function createToken()
    {

        $staff = Staff::byMobile($this->request->mobile);
        $smsStatus = $staff->generateToken();
        $staff->save();
        return $smsStatus;
    }
    // protected function createTokenApi()
    // {

    //     $staff = User::byEmail($this->request->email);
    //     $smsStatus = $staff->generateTokenApi();
    //     $staff->save();
    //     return $smsStatus;
    // }
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->user());
        // $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();
        Auth::guard('web')->logout();
        // Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
