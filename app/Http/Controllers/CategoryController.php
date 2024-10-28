<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TempImage;
use Dotenv\Parser\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories=Category::latest();
        if(!empty($request->get('keyword'))){
            $categories=$categories->where('name','like','%'.$request->get('keyword').'%');
        }
        //
        $categories=$categories->paginate(10);
        // $data['categories']=$category;
        return view('admin.category.list',compact('categories'));
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required | unique:categories',
        ]);
        if($validator->passes()){
            $category=new Category();
            $category->name=$request->name;  
            $category->slug=$request->slug;
            $category->status=$request->status;
            $category->showHome=$request->showHome;
            $category->save();

            if(!empty($request->image_id)){
                $tempImage=TempImage::find($request->image_id);
                $extArray=explode('.',$tempImage->name);
                $ext=last($extArray);
                $newImageName=$category->id.'.'.$ext;
                $sPath=public_path().'/temp/'.$tempImage->name;
                $dPath=public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // Generate Image Thumbnail
                $ddPath=public_path().'/uploads/category/thumb/'.$newImageName;
                $img=Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450,600,function ($constraint){
                    $constraint->upsize();
                });
                $img->save($ddPath);

                $category->image=$newImageName;
                $category->save();

            }

            $request->session()->flash('success', 'Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ], 200);
                                                                                                                                                                
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoryId, Request $request)
    {
        //
        $category=Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($categoryId,Request $request)
    {
        //  
        $category=Category::find($categoryId);
        if(empty($category)){
            $request->session()->flash('error', 'Category not Found');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Category not Found',
            ]);
        }

        $validator=Validator::make($request->all(),[
            'name'=> 'required',
            'slug'=> 'required| unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){
            $category->name=$request->name;
            $category->slug=$request->slug;
            $category->status=$request->status;
            $category->showHome=$request->showHome;
            $oldImage=$category->image;
            $category->save();
            
            if(!empty($request->image_id)){
                $tempImage=TempImage::find($request->image_id);
                $extArray=explode('.',$tempImage->name);
                $ext=last($extArray);
                $newImageName=$category->id.'-'.time().'.'.$ext;
                $sPath=public_path().'/temp/'.$tempImage->name;
                $dPath=public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // $category->image=$newImageName;
                // $category->save();
                // Generate Image Thumbnail
                $ddPath=public_path().'/uploads/category/thumb/'.$newImageName;
                $img=Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450,600,function ($constraint){
                    $constraint->upsize();
                });
                $img->save($ddPath);
    
                $category->image=$newImageName;
                $category->save();


                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);

                
    
            }

            $request->session()->flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ], 200);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }


        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoryId,Request $request)
    {
   
        $category=Category::find($categoryId);
        if(empty($category)){
            // return redirect()->route('categories.index');
            $request->session()->flash('error','Category not Found');
            return response()->json([
                'status'=>true,
                'message'=>'Category not Found'
            ]);

        }

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);
        $category->delete();
        $request->session()->flash('success','Category deleted successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Category deleted successfully'
        ]);
    }
}
