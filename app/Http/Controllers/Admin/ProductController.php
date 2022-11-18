<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(){
        $product = Product::orderByDesc('id')->get();
        if(count($product) > 0){
            foreach($product as $key=>$val){
                if(!empty($val->image)){
                    $product[$key]['image'] =  url('/product/'.$val->image);
                }
                else{
                    $product[$key]['image'] = url('/product/default.jpeg');
                }
            }
            return response()->json([
                'status' => 200,
                'message' => "Product list.",
                'product' => $product
            ], 200);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:products,name",
            "slug" => "required",
            "category_name" => "required",
            "brand" => "required",
            "qty" => "required",
            "selling_price" => "required|numeric",
            "original_price" => "required|numeric"
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            if($request->hasFile('image'))
            {
                $file= $request->file('image');
                $extension= $file->getClientOriginalExtension();
                $imageName = time().'.'.$extension;
                $file-> move('product/', $imageName);
                // $path               = $request->file('image')->storeAs('public/product', $imgageName);                            
            }
            else{
                $imageName = '';
            }
            $save = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_name,
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
                'small_description' => $request->small_description,
                'long_description' => $request->long_description,
                'brand' => $request->brand,
                'selling_price' => $request->selling_price,
                'original_price' => $request->original_price,
                'qty' => $request->qty,
                'image' => $imageName
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
        $product = Product::find($id);
        if (!empty($product)) {
            return response()->json([
                'status' => 200,
                'message' => "product list.",
                'product' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Data not found.",
                'product' => []
            ], 404);
        }
    }
}
