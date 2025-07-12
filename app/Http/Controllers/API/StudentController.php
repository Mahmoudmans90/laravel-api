<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::get();
        return response()->json([
            "status" =>"success",
            "data" => $students
        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validetor = Validator::make($request->all() , [
            "name" => "required|min:4",
            "email" => "required|unique:students,email",
            "gender" => "required"
        ]);

        if ($validetor->fails()) {
            return response()->json([
                "status" =>"fail",
                "message" => $validetor->errors()
            ] , 400);


        }

        $data = $request->all();
        Student::create($data);

        return response()->json([
            "status" =>"Success" ,
            "message" => "student created successfuly"
        ] , 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                "status" => "fail",
                "message" => "no Student found"
            ],400);
        }
        return response()->json([
            "status" => "success",
            "data" => $student
        ],200) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validetor = Validator::make($request->all() , [
            "name" => "required|min:4",
            "email" => "required|email|unique:students,email,$id",
            "gender" => "required"
        ]);

        if ($validetor->fails()) {
            return response()->json([
                "status" => "fail",
                "message" => $validetor->errors(),
                
            ],400);
        }
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                "status" => "fail",
                "message" => "no student found"
            ] , 404);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->gender = $request->gender;
        $student->save();
        return response()->json([
            "status" => "success" ,
            "data" => $student
        ] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                "status" => "fail",
                "message" => "student not found"
            ],404);
        }
        $student->delete();
        return response()->json([
            "status" => "success",
            "message" => "student deleted successuly"
        ] , 201);
    }
}
