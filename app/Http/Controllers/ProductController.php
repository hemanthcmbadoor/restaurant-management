<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Validator;

class ProductController extends Controller
{
    public function add(Request $request)
    {
        $rules = array(
            'name'          => 'required',
            'price'         => 'required',
            'category_id'   => 'required',
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

            $product =  Product::create([
                'name'          => $request->input('name'),
                'description'   => $request->input('description'),
                'price'         => $request->input('price'),
                'category_id'   => $request->input('category_id'),
                'status'        => 'true'
            ]);

            return response()->json(
                [
                    'status' => 1,
                    'message' => $product
                ], 200);
        }
    }

    public function get()
    {
        $product = Product::select('products.*', 'categories.name as category_name')
                                ->join('categories','products.category_id','=','categories.id')
                                ->orderBy('products.name','ASC')
                                ->get();

        return response()->json(
            [
                'status' => 1,
                'message' => $product
            ], 200);
    }

    public function getByCategory($id)
    {
        $product = Product::select('products.*', 'categories.name as category_name')
                                ->join('categories','products.category_id','=','categories.id')
                                ->where('categories.id', '=', $id)
                                ->where('products.status','true')
                                ->orderBy('products.name','ASC')
                                ->get();

        return response()->json(
            [
                'status' => 1,
                'message' => $product
            ], 200);
    }
    
    public function getById($id)
    {
        $product = Product::select('products.*', 'categories.name as category_name')
                                ->join('categories','products.category_id','=','categories.id')
                                ->where('products.id', '=', $id)
                                ->orderBy('products.name','ASC')
                                ->get();

        return response()->json(
            [
                'status' => 1,
                'message' => $product
            ], 200);
    }

    public function update(Request $request)
    {
        $rules = array(
            'name'          => 'required',
            'price'         => 'required',
            'category_id'   => 'required|exists:categories,id',
            'id'            => 'required|exists:products,id'
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

            $product = Product::find($request->input('id'));

            if($product){

                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->category_id = $request->input('category_id');

                $product->save();

                return response()->json(
                        [
                            'status' => 1,
                            'message' => 'Product Updated Successfully'
                        ], 200);

            } else {
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Product Not Found'
                    ], 400);
            }
        }
    }

    public function updateStatus(Request $request)
    {
        $rules = array(
            'status'        => 'required',
            'id'            => 'required|exists:products,id'
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

            $product = Product::find($request->input('id'));

            if($product){

                $product->status = $request->input('status');

                $product->save();

                return response()->json(
                        [
                            'status' => 1,
                            'message' => 'Product Status Updated Successfully'
                        ], 200);

            } else {
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Product Not Found'
                    ], 400);
            }
        }
    }

    public function delete($id)
    {
        $rules = array(
            'id' => 'required|exists:products,id',
        );

        $validator = Validator::make(['id' => $id], $rules);

        if($validator->fails()){

            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);
        }
        else{

            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Product Deleted Successfully'
                ], 200);
        }
    }
}
