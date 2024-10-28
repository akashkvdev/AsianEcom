<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    //
    public function index(Request $request){
        $products=Product::latest('id')->with('product_images');
        if($request->get('keyword')!=""){
            $products=$products->where('title','like','%'.$request->keyword.'%');
        }
        $products=$products->paginate();
        $data['products']=$products; 
        return view('admin.products.list',$data);
    }

    public function create(){
        $categories=Category::orderBy('name','ASC')->get();
        $brands=Brand::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        $data['brands']=$brands;
        return view('admin.products.create',$data);
    }

    public function store(Request $request){
        $rules=[
            'title'=>'required',
            'slug'=>'required|unique:products',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products',
            'track_qty'=>'required:in:Yes,No',
            'category'=>'required:numeric',
            'is_featured'=>'required:in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty=='Yes'){
            $rules['qty']='required|numeric';
        }
        $validator=Validator::make($request->all(),$rules);

        if($validator->passes()){
                    $product=new Product();
                    $product->title=$request->title;
                    $product->slug=$request->slug;
                    $product->description=$request->description;
                    $product->short_description=$request->short_description;
                    $product->shipping_returns=$request->shipping_returns;
                    $product->price=$request->price;
                    $product->compare_price=$request->compare_price;
                    $product->sku=$request->sku;
                    $product->barcode=$request->barcode;
                    $product->track_qty=$request->track_qty;
                    $product->qty=$request->qty;
                    $product->status=$request->status;
                    $product->category_id=$request->category;
                    $product->sub_category_id=$request->sub_category;
                    $product->brand_id=$request->brand;
                    $product->is_featured=$request->is_featured;
                    $product->related_products=(!empty($request->related_products)) ? implode(',',$request->related_products):'';
                    $product->save();

                    if (!empty($request->image_array)) {
                        foreach ($request->image_array as $temp_image_id) {
                            // Get temp image information
                            $tempImageInfo = TempImage::find($temp_image_id);
                    
                            if ($tempImageInfo) {
                                // Extract extension
                                $extArray = explode('.', $tempImageInfo->name);
                                $ext = last($extArray);
                    
                                // Create new ProductImage record
                                $productImage = new ProductImage();
                                $productImage->product_id = $product->id;
                    
                                // Generate unique image name
                                $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                                $productImage->image = $imageName;
                                $productImage->save();
                    
                                // Define source path for temp image
                                $sourcePath = public_path('/temp/' . $tempImageInfo->name);
                    
                                // Large Image
                                $largeDestPath = public_path('/uploads/product/large/' . $imageName);
                                $image = Image::make($sourcePath);
                                $image->resize(1400, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                $image->save($largeDestPath);
                    
                                // Small Image
                                $smallDestPath = public_path('/uploads/product/small/' . $imageName);
                                $image = Image::make($sourcePath);
                                $image->fit(300, 300);
                                $image->save($smallDestPath);
                            }
                        }
                    }
                    

                    session()->flash('success','Product added successfully');
                    return response()->json([
                        'status'=>true,
                        'message'=>'Product added successfully'
                    ]);
                    
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }


    // Edit Product
    public function edit($productId,Request $request){
        $products=Product::find($productId);
        if(empty($products)){
            return redirect()->route('products.index')->with('error','Product not found');
        }
        // Fetch Product Images
        $productImages=ProductImage::where('product_id',$products->id)->get();
        $subCategories=SubCategory::where('category_id',$products->category_id)->get();
        $categories=Category::orderBy('name','ASC')->get();
        $brands=Brand::orderBy('name','ASC')->get();

        $relatedProducts=[];
        if($products->related_products!=''){
            $productArray=explode(',',$products->related_products);
           $relatedProducts= Product::whereIn('id',$productArray)->get();

        }
        $data['categories']=$categories;
        $data['subCategories']=$subCategories;
        $data['brands']=$brands;
        $data['product']=$products;
        $data['productImages']=$productImages;
        $data['relatedProducts']=$relatedProducts;

        return view('admin.products.edit',$data);
       
    }


    public function update($productId, Request $request)
    {
        $products=Product::find($productId);
        $rules=[
            'title'=>'required',
           'slug'=> 'required| unique:products,slug,'.$products->id.',id',
            'price'=>'required|numeric',
           'sku'=> 'required| unique:products,sku,'.$products->id.',id',
            'track_qty'=>'required:in:Yes,No',
            'category'=>'required:numeric',
            'is_featured'=>'required:in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty=='Yes'){
            $rules['qty']='required|numeric';
        }
        $validator=Validator::make($request->all(),$rules);

        if($validator->passes()){
                   
                    $products->title=$request->title;
                    $products->slug=$request->slug;
                    $products->description=$request->description;
                    $products->short_description=$request->short_description;
                    $products->related_products=(!empty($request->related_products)) ? implode(',',$request->related_products):'';
                    $products->shipping_returns=$request->shipping_returns;
                    $products->price=$request->price;
                    $products->compare_price=$request->compare_price;
                    $products->sku=$request->sku;
                    $products->barcode=$request->barcode;
                    $products->track_qty=$request->track_qty;
                    $products->qty=$request->qty;
                    $products->status=$request->status;
                    $products->category_id=$request->category;
                    $products->sub_category_id=$request->sub_category;
                    $products->brand_id=$request->brand;
                    $products->is_featured=$request->is_featured;
                    $products->save();

                
                    

                    session()->flash('success','Product updated successfully');
                    return response()->json([
                        'status'=>true,
                        'message'=>'Product added successfully'
                    ]);
                    
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }



    public function destroy($id, Request $request) {
        $product = Product::find($id);
        
        if (empty($product)) {  // Corrected $products to $product
            session()->flash('error', 'Product Not Found');  // Changed flash message type to 'error'
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
                'notFound' => true
            ]);
        }
    
        $productImages = ProductImage::where('product_id', $id)->get();
        
        if ($productImages->isNotEmpty()) {  // Fixed the check for non-empty product images
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/' . $productImage->image));  // Deleting the actual file
                File::delete(public_path('uploads/product/small/' . $productImage->image));  // Corrected the file deletion logic
            }
            ProductImage::where('product_id', $id)->delete();  // Delete associated product images
        }
    
        $product->delete();  // Delete the product itself
        session()->flash('success', 'Product deleted successfully');
    
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
        ]);
    }



    public function getproducts(Request $request){
        
        if($request->term !=""){
            $products=Product::where('title','like','%'.$request->term. '%')->get();

            if($products !=null){
                foreach($products as $product){
                    $tempProduct[]=array('id'=>$product->id,'text'=>$product->title);
                }
            }
        }
        return response()->json([
            'tags'=>$tempProduct,
            'status'=>true
        ]);
    }
    
}
