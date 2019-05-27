<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\SubCategory;
use DB;
use Session;
use Excel;
use File;
use App\Http\Resources\ProductListResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        ProductListResource::withoutWrapping();

        return  ProductListResource::collection(Product::all());
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

                        $category = [     
                            'name' => $value->category,
                        ];
                        $cat = Category::updateOrCreate($category);

                        
                        $subCategory = [
                            'category_id' => $cat->id,
                            'name' => $value->sub_category,
                        ];
                        
                        $subcat = SubCategory::updateOrCreate($subCategory);
                        
                        $product[] = [
                            'category_id' => $cat->id,
                            'sub_category_id' => $subcat->id,
                            'part_number' => $value->part_number,
                            'description' => $value->description,
                        ];
                    }

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
