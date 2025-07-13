<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            "name" => "required|min:4",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
            "password_confirmation" => "required|min:8",

        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" =>"fail",
                "message" => $validator->errors()
            ] , 400);
        }
        $data = $request->all();
        $imagePath = null;
        if ($request->hasFile("profile_picture" ) && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $img_name  = time().'_'.$file->getClientOriginalName();
            $file->move(public_path("storage/profile") , $img_name);
             $imagePath = "storage/profile/$img_name";
        }
        $data['profile_picture'] = $imagePath;
        User::create($data);

        return response()->json([
            "status" => "success",
            "message" => "new user created successfuly"
        ] , 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all() , [
            "email" => "required|email" ,
            "password" => "required|min:8"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "fail" ,
                "message" => $validator->errors()
            ] , 400);
        }

        if (Auth::attempt(["email" => $request->email , "password" => $request->password])) {
            $user = Auth::user();
            $response['token'] = $user->createToken('BlogApp')->plainTextToken;
            $response['name'] = $user->name; 
            $response['email'] = $user->email; 
            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfuly',
                "data" => $response
            ] , 200);
        }else {
            return response()->json([
                "status" => "fail" ,
                "message" => "Invalid creadintials"
            ] , 400);
        }
    }

   public function profile() {
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            "status" => "fail" ,
            "message" => "please pass authrisation token"
        ] , 400);
    }
    return response()->json([
        "status" => "success" ,
        "data" => $user
    ] , 200);
   }
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            "status" => "success" ,
            "message" => "loged out successfuly"
        ] , 200);
    }

}
