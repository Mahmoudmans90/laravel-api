<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BlogCategory::get();
        return response()->json([
            'status' => 'success' ,
            'count' => count($data),
            'data' => $data
        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail' ,
                'message' => $validator->errors()
            ] , 400);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        BlogCategory::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'category created successfuly'
        ] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail' ,
                'message' => $validator->errors()
            ] , 400);

        }

        $category = BlogCategory::find($id);
        if(!$category){
            return response()->json([
                'status' => 'fail',
                'message' => 'no category found'
            ], 404);

        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
        return response()->json([
            'status' => 'success',
            'message' => 'data updated successfuly'
        ] , 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'fail',
                'message' => 'No Category found',
            ] , 404);
        }

        $category->delete();
        return response()->json([
            'status' => 'success' ,
            'message' => 'category deleted successfuly'
        ] , 200);
    }
}
