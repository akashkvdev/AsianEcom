<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    // Display the form to create a new brand
    public function create()
    {
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.brand.create', ['brands' => $brands]);
    }

    // Store a newly created brand in the database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand created successfully.');
            return response([
                'status' => true,
                'message' => 'Brand created successfully.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // Display a listing of the brands
    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brand.list', ['brands' => $brands]);
    }

    // Show the form for editing a brand
    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('brands.index');
        }

        return view('admin.brand.edit', ['brand' => $brand]);
    }

    // Update the specified brand in the database
    public function update($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            $request->session()->flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id,
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand updated successfully.');
            return response([
                'status' => true,
                'message' => 'Brand updated successfully.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // Remove the specified brand from the database
    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            $request->session()->flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $brand->delete();
        $request->session()->flash('success', 'Brand deleted successfully.');
        return response([
            'status' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }
}
