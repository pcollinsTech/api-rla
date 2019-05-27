<?php

namespace App\Http\Controllers;

use App\Product;
use DB;
use Excel;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        return response()->json(Product::all());
    }

    
 
    public function import(Request $request){
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
                    
                    foreach ($data as $key => $value) {

                        $category[] = [     
                            'category' => $value->category,
                        ];

                        $product[] = [
                            'part_number' => $value->part_number,
                            'description' => $value->description,
                        ];

                        $subCategory[] = [
                            'sub_category' => $value->sub_category,
                        ];
                    }
                    
                    $cateogry = App\Category::updateOrCreate($category);
                    $subCateogry = App\SubCategory::updateOrCreate($subCategory);


                    if(!empty($product)){
                        
                        $insertData = DB::table('products')->insert($product);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
 
                return back();
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }

}
