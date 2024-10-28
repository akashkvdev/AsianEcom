<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    //

    public function create(Request $request)
    {
        $countries = Country::get();
        $data['countries'] = $countries;
        $shippingCharges=ShippingCharge::select('shipping_charges.*','countries.name')->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create', $data);
    }

    public function store(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            $count= ShippingCharge::where('country_id',$request->country)->count();
            if($count>0){
                session()->flash('error', 'Shipping already added ');
             return response()->json([
                 'status' => true,
             ]);
            }
           
            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Shipping added successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($id){
        $shippingCharge=ShippingCharge::find($id);
        $countries = Country::get();
        $data['countries'] = $countries;
        $data['shippingCharge'] = $shippingCharge;
        return view('admin.shipping.edit', $data);
    }


    
    public function update($id, Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }
    
        // Find the ShippingCharge record by ID
        $shipping = ShippingCharge::find($id);
    
        // Check if the record exists
        if (!$shipping) {
            return response()->json([
                'status' => false,
                'message' => 'Shipping record not found',
            ], 404); // 404 Not Found
        }
    
        // Update the shipping charge with validated data
        $shipping->country_id = $request->country;
        $shipping->amount = $request->amount;
        $shipping->save();
    
        // Flash success message and return response
        session()->flash('success', 'Shipping updated successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Shipping updated successfully',
        ], 200); // 200 OK
    }

    public function destroy($id){
        $shippingCharge=ShippingCharge::find($id);
        if($shippingCharge==null){
            session()->flash('error','Shipping Not Found');
            return response()->json([
                'status'=>true
            ]);
        }
        $shippingCharge->delete();
        session()->flash('success','Shipping delted successfully');
        return response()->json([
            'status'=>true
        ]);
    }   
    
}
