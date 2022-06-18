<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Bill;
use Validator;
use PDF;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    public function generateReport(Request $request)
    {  
        $rules = array(
            'name'              => 'required',
            'email'             => 'required',
            'contact_number'    => 'required',
            'payment_method'    => 'required',
            'total_amount'      => 'required',
            'product_details'   => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){

            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()
                ], 400);

        } else {

            //$jsondata = "[{\"id\":1, \"name\": \"Black Coffie\", \"price\":99, \"total\":99, \"category\": \"Coffie\", \"quantity\": \"1\"}]";

            $orderDetails = json_decode($request->input('product_details'), true);
            //$orderDetails = json_decode($jsondata, true);

            $uuid = Str::uuid()->toString();

            //dd($request->input('product_details'),$jsondata);

            $bill =  Bill::create([
                'uuid'              => $uuid,
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'contact_number'    => $request->input('contact_number'),
                'payment_method'    => $request->input('payment_method'),
                'total'             => $request->input('total_amount'),
                'created_by'        => $request->input('email'),
                'product_details'   => $request->input('product_details'),
            ]);

            $pdf = PDF::loadView('pdf.report', [
                'productDetails' => $orderDetails,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'contactNumber' => $request->input('contact_number'),
                'paymentMethod' => $request->input('payment_method'),
                'totalAmount'   => $request->input('total_amount'),
            ]);

            $filename = $uuid.'.pdf';

            Storage::put('bill_pdf/'.$filename, $pdf->output());

            return response()->json(
                [
                    'status' => 1,
                    'message' => ['uuid' => $uuid],
                ], 200);
        }
    }

    public function downloadPdf(Request $request)
    {

        $fileName   = $request->input('uuid').'.pdf';

        $headers = ['Content-Type: application/pdf'];
        
        $pathToFile = storage_path('app/bill_pdf/'.$fileName);
        
        if (!file_exists($pathToFile)) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'File Not exists'
                ], 400);
        }
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function getBill()
    {
        $bill = Bill::all();

        return response()->json(
            [
                'status' => 1,
                'message' => $bill
            ], 200);
    }

    public function delete($id)
    {
        $rules = array(
            'id' => 'required|exists:bills,id',
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

            $bill = Bill::findOrFail($id);

            $bill->delete();

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Bill Deleted Successfully'
                ], 200);
        }
    }
}
