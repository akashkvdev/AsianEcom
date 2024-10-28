<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //Product Home Page
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];
    
        // Check if brand filter is applied
        if ($request->has('brand')) {
            $brandsArray = explode(',', $request->get('brand'));  // Convert comma-separated values into array
        }
    
        // Retrieve categories and brands
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->with('sub_categories')->get();
        $brands = Brand::where('status', 1)->orderBy('name', 'ASC')->get();
    
        // Initialize product query
        $products = Product::where('status', 1);
    
        // Filter by category if slug is present
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }
    
        // Filter by subcategory if slug is present
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }
    
        // Filter by selected brands
        if (!empty($brandsArray)) {
            $products->whereIn('brand_id', $brandsArray);
        }
    
        // Filter by price range
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $priceMin = (int)$request->get('price_min');
            $priceMax = (int)$request->get('price_max');
    
            if ($priceMax == 1000) {
                $products->where('price', '>=', $priceMin);  // No upper limit
            } else {
                $products->whereBetween('price', [$priceMin, $priceMax]);
            }
        }
    
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->get('sort')) {
                case 'latest':
                    $products->orderBy('id', 'DESC');
                    break;
                case 'price_asc':
                    $products->orderBy('price', 'ASC');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'DESC');
                    break;
                default:
                    $products->orderBy('id', 'DESC');
                    break;
            }
        } else {
            $products->orderBy('id', 'DESC');
        }
    
        // Retrieve products
        $products = $products->paginate(6);
    
        // Prepare data for the view
        $data = [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $categorySelected,
            'subCategorySelected' => $subCategorySelected,
            'brandsArray' => $brandsArray,
            'priceMax' => $request->get('price_max', 1000),  // Default to 1000 if not provided
            'priceMin' => $request->get('price_min', 0),     // Default to 0 if not provided
            'sort' => $request->get('sort')     // Default to 0 if not provided
        ];
    
        // Return view with data
        return view('front.shop', $data);
    }



    // Products Individual
    public function product($slug){
        // echo $slug;
        $product=Product::where('slug',$slug)->with('product_images')->first();
        if($product==null){
            abort(404); 
        }
     
        // Fetch Related Product
        $relatedProducts=[];
        if($product->related_products!=''){
            $productArray=explode(',',$product->related_products);
           $relatedProducts= Product::whereIn('id',$productArray)->with('product_images')->get();

        }
        $data['product']=$product;
        $data['relatedProducts']=$relatedProducts;
        return view('front.product',$data);

    }


    
    
    
}
