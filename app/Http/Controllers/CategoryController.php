<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    public function add(Request $request)
    {
        $rules = array(
            'name' => 'required'
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

            $category =  Category::create([
                'name'  => $request->input('name')
            ]);

            return response()->json(
                [
                    'status' => 1,
                    'message' => $category
                ], 200);
        }
    }

    public function get()
    {
        $category = Category::orderBy('name', 'ASC')->get();

        return response()->json(
            [
                'status' => 1,
                'message' => $category
            ], 200);
    }

    public function update(Request $request)
    {
        $rules = array(
            'id' => 'required',
            'name' => 'required'
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

            $category = Category::find($request->input('id'));

            if($category){

                $category->name = $request->input('name');
                $category->save();

                return response()->json(
                        [
                            'status' => 1,
                            'message' => 'Category Updated Successfully'
                        ], 200);

            } else {
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Category Not Found'
                    ], 400);
            }
        }
    }
}
