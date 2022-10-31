<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use DataTables;

class ProductController extends Controller
{
    public function index(){

        return view('productList');
    }
    public function getAllProducts(Request $request){
        $columns = array(
            0 => 'product_id',
            1 => 'product_title',
            2 => 'description',
            3 => 'thumbnail',
            4 => 'category_title',
            5 => 'subcategory_title',
            6 => 'price',
            7=>'product_id'
        );
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $limit = $request->input('length');
        $start = $request->input('start');
        $searchValue = $request->input('search.value');

        $allProductsCount = Product::count();

        if (!empty($searchValue)) {
            
            $filteredProducts = Product::join('subcategories','products.subcategory_id', '=', 'subcategories.subcategory_id')
                ->join('categories','subcategories.category_id', '=', 'categories.category_id')
                ->selectRaw('products.id as product_id,products.title as product_title , products.description, products.price, products.thumbnail, 
                            subcategories.title as subcategory_title,subcategories.id as subcategory_id, 
                            categories.title as category_title, categories.id as category_id')
                ->skip($start)
                ->take($limit)
                ->where('product_title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('category_title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('subcategory_title', 'LIKE', '%' . $searchValue . '%')
                ->orderBy($order, $dir)
                ->get();
        } else {
            $filteredProducts =Product::join('subcategories','products.subcategory_id', '=', 'subcategories.id')
                ->join('categories','subcategories.category_id', '=', 'categories.id')
                ->selectRaw('products.id as product_id,products.title  as product_title , products.description, products.price, products.thumbnail, 
                subcategories.title as subcategory_title,subcategories.id as subcategory_id, 
                categories.title as category_title, categories.id as category_id')
                ->skip($start)
                ->take($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        return response()->json([
            "data" => $filteredProducts,
            "draw" => $request->draw,
            "recordsTotal" => $allProductsCount,
            "recordsFiltered" => $allProductsCount
        ]);
    }

    public function store(Request $request)
    {
        //dd($request->all() );
        $product = new Product;

        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->thumbnail = $request->input('thumbnail');
        $product->price = $request->input('price');

        $product->save();
    
        return response()->json(['success'=>'Product added Successfully!!']);

    }


    public function update()
    {
        # code...
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if($product){
            $product->delete();
        }
        else{
            return response()->json(['failed'=>'Product not found.']);
        }

        return response()->json(['success'=>'Product deleted Successfully!!']);

    }
}