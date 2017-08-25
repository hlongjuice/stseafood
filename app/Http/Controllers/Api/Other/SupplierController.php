<?php

namespace App\Http\Controllers\Api\Other;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function getAllSupplier(){
        $suppliers=Supplier::all();
        return response()->json($suppliers);
    }

    /*Add Supplier*/
    public function addSupplier(Request $request){
        $result=Supplier::create([
           'name'=>$request->input('name')
        ]);
        return response()->json($result);
    }
    /*Update*/
    public function updateSupplier(Request $request)
    {

        $result = Supplier::where('id', $request->input('id'))
            ->update([
                'name' => $request->input('name')
            ]);
        return response()->json($result);
    }
}
