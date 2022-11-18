<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('id', 'desc')->get();
        if (count($category) > 0) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ], 200);
        } else {
            return response()->json([
                'status' => 200,
                'category' => []
            ], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "slug" => "required",
            "name" => "required",
            "meta_title" => "required"
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            $save = Category::create([
                'slug' => $request->slug,
                'name' => $request->name,
                'description' => $request->description,
                'description' => $request->description,
                'status' => $request->status ? 1 : 0,
                'meta_title' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description
            ]);
            if ($save) {
                return response()->json([
                    'status' => 200,
                    'message' => "Category created succefully."
                ], 200);
            }
        }
    }
    public function edit(Request $request, $id)
    {
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json([
                'status' => 200,
                'message' => "Category list.",
                'category' => $category
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Data not found.",
                'category' => []
            ], 404);
        }
    }
    public function update(Request $request)
    {
        $category = Category::find($request->id);
        if (!empty($category)) {
            $category->slug = $request->slug;
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status;
            $category->meta_title = $request->meta_title;
            $category->meta_keywords = $request->meta_keywords;
            $category->meta_description = $request->meta_description;
            $update = $category->save();
            if ($update) {
                return response()->json([
                    'status' => 200,
                    'message' => "Category update successfully.",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Data not found.",
                'category' => []
            ], 404);
        }
    }
    public function delete($id)
    {
        $delete = Category::FindOrFail($id);
        if ($delete->delete()) {
            return response()->json([
                'status' => 200,
                'message' => "Category deleted successfully.",
            ], 200);
        }
    }
    public function List(){
        $category = Category::select('id','name')->where('status',0)->get();
        if (count($category) > 0) {
            return response()->json([
                'status' => 200,
                'message' => "Category list.",
                'category' =>$category
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "Data not found.",
                'category' => []
            ], 404);
        }
    }
}
