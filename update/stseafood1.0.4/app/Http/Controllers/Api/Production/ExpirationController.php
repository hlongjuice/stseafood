<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\Expiration;
use App\Models\Production\ExpirationBuild;
use App\Models\Production\ExpirationImage;
use App\Models\Production\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use File;

class ExpirationController extends Controller
{
    //Get Record
    public function getRecordByMonth(Request $request)
    {
        $records = Expiration::with('product', 'build')->whereYear('updated_at', $request->input('year'))
            ->whereMonth('updated_at', $request->input('month'))
            ->orderBy('updated_at', 'desc')->get()->groupBy('product.name');
        /*   foreach ($records as $record){
               $exp_date=Carbon::createFromFormat('Y-m-d',$record->mfd)->addDay($record->product->exp_day)->toDateString();
               $record->exp_date=$exp_date;
           }*/
        /*$results=collect([
        ]);*/
        return response()->json($records);
    }

    //get By Date
    public function getRecordByDate($date)
    {
        $records = Expiration::with('product', 'build', 'image')
            ->orWhere('mfd', $date)
            ->orderBy('updated_at', 'desc')->get()->groupBy('product.name');
        $results = collect([]);
        foreach ($records as $product_name => $values) {
            foreach ($values as $record) {
                $round_group = $record['build']->sortBy('round')->groupBy('round');
                $exp_date = Carbon::createFromFormat('Y-m-d', $record->mfd)->addMonthsNoOverflow($record->exp_date)->toDateString();
                $rounds = collect([]);
                foreach ($round_group as $key => $group) {
                    $sumOutside = $group->sum('q_outside');
                    $sumInside = $group->sum('q_inside');
                    $sumSticker = $group->sum('q_sticker');
                    $round = collect([
                        'round' => $key,
                        'data' => $group,
                        'sumOutSide' => $sumOutside,
                        'sumInSide' => $sumInside,
                        'sumSticker' => $sumSticker
                    ]);
                    $rounds->push($round);
                }

                $record->exp_date = $exp_date;
                $record->rounds = $rounds;
            }
            $data = collect([
                'product' => $product_name,
                'data' => $values
            ]);
            $results->push($data);
        }

        return response()->json($results);
    }

    //Add Record
    public function addExp(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $product=Product::where('id',$request->input('product_id'))->first();
            $exp = Expiration::where('product_id', $request->input('product_id'))
                ->where('mfd', $request->input('mfd'))
                ->where('code', $request->input('code'))
                ->first();
            if ($exp == null) {
                $exp = Expiration::create([
                    'product_id' => $request->input('product_id'),
                    'mfd' => $request->input('mfd'),
                    'code' => $request->input('code'),
                    'exp_date'=>$product->exp_date,
                    'date_format'=>$product->date_format
                ]);
            }
            $insideInput = 0;
            $insideSticker = 0;
            if ($request->input('multi_inside_num') != null) {
                $insideInput = (float)$request->input('multi_inside_num');
            }
            if ($request->input('multi_sticker_num') != null) {
                $insideSticker = (float)$request->input('multi_sticker_num');
            }
//
            $q_outside=(float)$request->input('q_box') * (float)$request->input('multi_outside_num');
            $q_inside =(float)$request->input('q_box') * $insideInput;
            $q_sticker=(float)$request->input('q_box') * $insideSticker;

            ExpirationBuild::create([
                'p_exp_id' => $exp->id,
                'round' => $request->input('round'),
                'q_box' => $request->input('q_box'),
                'q_inside' => $q_inside,
                'q_outside' => $q_outside,
                'q_sticker' => $q_sticker
            ]);
        });
        return response()->json($result);
    }

    //add Exp Image
    public function addExpImage(Request $request)
    {
        $oldImage = ExpirationImage::where('p_exp_id', $request->input('p_exp_id'))->first();
        $result = null;
        $image_path = null;
        $imgOutSide = null;
        $imgInSide = null;
        $imgSticker = null;
        foreach ($request->input('images') as $image) {
            $image_path = 'images/production/expiration/' . $request->input('p_exp_id') . '-' . $image['type'] . '.jpg';
            $newImage = Image::make($image['data'])->save($image_path);
            if ($newImage) {
                if ($image['type'] == 'outside') {
                    $imgOutSide = $image_path;
                } else if ($image['type'] == 'inside') {
                    $imgInSide = $image_path;
                } else if ($image['type'] == 'sticker') {
                    $imgSticker = $image_path;
                }
            }
        }
        if ($oldImage == null) {
            $result = ExpirationImage::create([
                'p_exp_id' => $request->input('p_exp_id'),
                'image_outside' => $imgOutSide,
                'image_inside' => $imgInSide,
                'image_sticker' => $imgSticker
            ]);
        } else {
            $result = $oldImage->update([
                'image_outside' => $imgOutSide,
                'image_inside' => $imgInSide,
                'image_sticker' => $imgSticker
            ]);
        }
        return response()->json($result);
    }

    //Update Exp
    public function updateExp(Request $request)
    {
        $result = Expiration::where('id', $request->input('id'))
            ->update([
                'product_id' => $request->input('product_id'),
                'mfd' => $request->input('mfd'),
                'code' => $request->input('code')
            ]);
        return response()->json($result);
    }

    //Update Exp Image
    public function updateExpImage(Request $request)
    {
        $date = Carbon::now()->toDateString();
        $randString=str_random(4);
        $oldImage = ExpirationImage::where('p_exp_id', $request->input('p_exp_id'))->first();
        $result = null;
        $image_path = null;
        $imgOutSide = $oldImage->image_outside;
        $imgInSide = $oldImage->image_inside;
        $imgSticker = $oldImage->image_sticker;
        foreach ($request->input('images') as $image) {
            $image_path = 'images/production/expiration/' . $request->input('p_exp_id') . '-' . $image['type'] . '.jpg';
            File::delete($image_path);
            $image_new_path = 'images/production/expiration/' . $date.$randString. $request->input('p_exp_id') . '-' . $image['type'] . '.jpg';
            $newImage = Image::make($image['data'])->save($image_new_path);
            if ($newImage) {
                if ($image['type'] == 'outside') {
                    $imgOutSide = $image_new_path;
                } else if ($image['type'] == 'inside') {
                    $imgInSide = $image_new_path;
                } else if ($image['type'] == 'sticker') {
                    $imgSticker = $image_new_path;
                }
            }
        }

        $result = $oldImage->update([
            'image_outside' => $imgOutSide,
            'image_inside' => $imgInSide,
            'image_sticker' => $imgSticker
        ]);
        return response()->json($result);
    }

    //Update Exp Build
    public function updateExpBuild(Request $request)
    {
        $insideInput = 0;
        $insideSticker = 0;
        if ($request->input('multi_inside_num') != null) {
            $insideInput = (float)$request->input('mul_inside_num');
        }
        if ($request->input('multi_sticker_num') != null) {
            $insideSticker = (float)$request->input('multi_sticker_num');
        }
        $q_outside = number_format((float)$request->input('q_box') * (float)$request->input('multi_outside_num'), 2);
        $q_inside = number_format((float)$request->input('q_box') * $insideInput, 2);
        $q_sticker = number_format((float)$request->input('q_box') * $insideSticker, 2);
        $result = ExpirationBuild::where('id', $request->input('id'))
            ->update([
                'round' => $request->input('round'),
                'q_box' => $request->input('q_box'),
                'q_inside' => $q_inside,
                'q_outside' => $q_outside,
                'q_sticker' => $q_sticker
            ]);
        return response()->json($result);
    }

    //Delete Exp
    public function deleteExp($id)
    {
        $result = DB::transaction(function () use ($id) {
            Expiration::where('id', $id)->delete();
            ExpirationImage::where('p_exp_id', $id)->delete();
            ExpirationBuild::where('p_exp_id', $id)->delete();
        });
        return response()->json($result);
    }

    //Delete ExpirationBuild
    public function deleteExpBuild($id)
    {
        $result = ExpirationBuild::where('id', $id)->delete();
        return response()->json($result);
    }

    //Delete  Expiration Image
    public function deleteExpImage(Request $request)
    {
        $oldImage = ExpirationImage::where('p_exp_id', $request->input('p_exp_id'))->first();
        $result = null;
        $image_path = null;
        $imgOutSide = null;
        $imgInSide = null;
        $imgSticker = null;
        $imageInputs = collect([]);
        $image = $request->input('images');
        $path = public_path('images/production/expiration/' . $request->input('p_exp_id') . '-' . $image['type'] . '.jpg');

        $deleteImage = File::delete($path);
        if ($image['type'] == 'outside') {
            $result = $oldImage->update([
                'image_outside' => null
            ]);
        } else if ($image['type'] == 'inside') {
            $result = $oldImage->update([
                'image_inside' => null
            ]);
        } else if ($image['type'] == 'sticker') {
            $result = $oldImage->update([
                'image_sticker' => null
            ]);
        }
        return response()->json($result);
    }

    //PD QC Checker
    public function expChecker(Request $request){
       $result=DB::transaction(function() use ($request){
            $exp_record=Expiration::where('id',$request->input('id'))->update([
               'pd_checker'=>$request->input('pd_checker'),
               'qc_checker'=>$request->input('qc_checker')
            ]);
        });
       return response()->json($result);
    }
}
