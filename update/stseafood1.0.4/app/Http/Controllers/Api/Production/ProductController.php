<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    //Get All
    public function getAll()
    {
       $products=Product::all()->sortBy('name',SORT_NATURAL)->values();
        return response()->json($products);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Product::create([
            'name'=>$request->input('name'),
            'date_format'=>$request->input('date_format'),
            'exp_date'=>$request->input('exp_date')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Product::where('id', $request->input('id'))
            ->update([
                'name'=>$request->input('name'),
                'date_format'=>$request->input('date_format'),
                'exp_date'=>$request->input('exp_date')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = Product::where('id', $id)->delete();
        return response()->json($result);
    }
}
