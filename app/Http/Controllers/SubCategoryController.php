<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

use function Ramsey\Uuid\v1;

class SubCategoryController extends Controller
{
    //

    public function create(){
        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        return view('admin.sub_category.create',$data);
    }

    public function store( Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required | unique:sub_categories',
            'category'=>'required',
            'status'=>'required'
        ]);


        if($validator->passes()){
            $subCategory=new SubCategory();
            $subCategory->name=$request->name;
            $subCategory->slug=$request->slug;
            $subCategory->status=$request->status;
            $subCategory->category_id=$request->category;
            $subCategory->showHome=$request->showHome;
            $subCategory->save();

            $request->session()->flash('success','Sub Category created successfully.');
            return response([
                'status'=>true,
                'message'=>'Sub Category created Successfully.']);

        }else{
            return response([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }


    public function index(Request $request)
    {
        $subcategories=SubCategory::select('sub_categories.*','categories.name as categoryName')
                                        ->latest('sub_categories.id')
                                        ->leftJoin('categories','categories.id','sub_categories.category_id');
        if(!empty($request->get('keyword'))){
            $subcategories=$subcategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');

            $subcategories=$subcategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        //
        $subcategories=$subcategories->paginate(10);
        // $data['categories']=$category;
        return view('admin.sub_category.list',compact('subcategories'));
      
    }

        public function edit($id,Request $request){
            $subCategory=SubCategory::find($id);
            if(empty($subCategory)){
                $request->session()->flash('error','Record not found');
                return redirect()->route('sub-categories.index');
            }

            $categories=Category::orderBy('name','ASC')->get();
            $data['categories']=$categories;
            $data['subCategory']=$subCategory;
            

            return view('admin.sub_category.edit',$data);  

        }


    public function update($id,Request $request){
        $subCategory=SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status'=>false,
                'notFound'=>true
            ]);

        }
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            // 'slug'=>'required | unique:sub_categories',
            'slug'=> 'required| unique:sub_categories,slug,'.$subCategory->id.',id',
            'category'=>'required',
            'status'=>'required'
        ]);


        if($validator->passes()){
            // $subCategory=new SubCategory();
            $subCategory->name=$request->name;
            $subCategory->slug=$request->slug;
            $subCategory->status=$request->status;
            $subCategory->category_id=$request->category;
            $subCategory->showHome=$request->showHome;
            $subCategory->save();

            $request->session()->flash('success','Sub Category updated successfully.');
            return response([
                'status'=>true,
                'message'=>'Sub Category updated Successfully.']);

        }else{
            return response([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }



    public function destroy($id, Request $request){
        $subCategory=SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status'=>false,
                'notFound'=>true
            ]);

        }
        $subCategory->delete();
        $request->session()->flash('success','Sub Category deleted successfully.');
        return response([
            'status'=>true,
            'message'=>'Sub Category deleted Successfully.']);
        
    }



}
