<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'contact_number' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $user =  User::create([
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'password'          => Hash::make($request->input('password')),
                'plain_password'    => $request->input('password'),
                'contact_number'    => $request->input('contact_number'),
                'status'            => 'false',
                'role'              => 'user'
            ]);

            $response = [
                'token' => $user->createToken(time())->plainTextToken
            ];

            return response()->json(
                [
                    'status' => 1,
                    'message' => $response
                ], 200);
        }
    }

    public function login(Request $request)
    {

        $rules = array(
            'email' => 'required',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $user = User::where('email', $request->input('email'))->first();

            if($user){

                if(Hash::check( $request->input('password'), $user->getAuthPassword() )){

                    if($user->status == 'true'){

                        $response = [
                            'token' => $user->createToken(time())->plainTextToken
                        ];

                        return response()->json(
                            [
                                'status' => 1,
                                'message' => $response
                            ], 200);

                    } else {

                        return response()->json(
                            [
                                'status' => 0,
                                'message' => 'Wait for Admin Approval'
                            ], 401);
                    }

                } else {

                    return response()->json(
                        [
                            'status' => 0,
                            'message' => 'Invalid Password'
                        ], 401);

                }
            } else {

                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Invalid Email'
                    ], 401);
            }
            
        }
    }

    public function forgotPassword(Request $request)
    {

        $rules = array(
            'email' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $user = User::where('email', $request->input('email'))->first();

            if($user){

                $details = [
                    'email' => $user->email,
                    'password' => $user->plain_password,
                    'url' => env('APP_FRONTEND_URL', 'http://localhost:4200')
                ];
               
                Mail::to('hemanth.cm@fingent.com')->send(new ForgotPasswordMail($details));

                return response()->json(
                    [
                        'status' => 1,
                        'message' => 'Password Sent Successfully to your Account'
                    ], 200);
            } else {
                return response()->json(
                    [
                        'status' => 1,
                        'message' => 'Password Sent Successfully to your Account'
                    ], 200);
            }
        }

        
    }

    public function getUserList()
    {
        $user = User::where('role', '=', 'user')->get();
        return response()->json(
            [
                'status' => 1,
                'message' => $user
            ], 200);
    }
    
    public function updateUserStatus(Request $request)
    {
        $rules = array(
            'id' => 'required',
            'status' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $user = User::find($request->input('id'));

            if($user){

                $user->status = $request->input('status');
                $user->save();

                return response()->json(
                        [
                            'status' => 1,
                            'message' => 'Status Updated Successfully'
                        ], 200);

            } else {
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'User Not Found'
                    ], 400);
            }
        }
    }

    public function checkToken()
    {
        return response()->json(
            [
                'status' => 1,
                'message' => 'true'
            ], 200);
    }

    public function changePassword(Request $request)
    {
        $rules = array(
            'email' => 'required|exists:users,email',
            'oldpassword' => 'required|',
            'newpassword' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $user = User::where('email', $request->input('email'))->first();

            if(Hash::check($request->input('oldpassword'), $user->password)){

                $new_password = Hash::make($request->input('newpassword'));

                if(Hash::check($request->input('oldpassword'), $new_password)){

                    return response()->json(
                        [
                            'status' => 0,
                            'message' => 'New Password same as Old Password'
                        ], 400);

                } else {

                    $user->password = Hash::make($request->input('newpassword'));
                    $user->plain_password = $request->input('newpassword');
                    $user->save();

                    return response()->json(
                            [
                                'status' => 1,
                                'message' => 'User Password Updated Successfully'
                            ], 200);
                }

            } else {

                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Invalid Old Password'
                    ], 400);

            }
            
        }
    }

}
