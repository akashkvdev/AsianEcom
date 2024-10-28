<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    //


    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        if (Cart::count() > 0) {
            // echo "Product is already is exist in cart";
            // Product found in cart
            // chekc if this product already added in your cart
            // if product  not found in cart , then add  product  in cart
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                $status = true;
                $message ='<strong>'. $product->title .'</strong> added in your cart successfully';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title . 'already added in cart';
            }
        } else {
            // Cart is empty
            // echo "Cart is empty now adding  a product  in cart";
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

            $status = true;
            $message ='<strong>'. $product->title .'</strong> added in your cart successfully';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {

        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                // Cart
                Cart::update($rowId, $qty);
                $message = 'Cart updated Successfuly';
                $status = true;
            } else {
                $message = 'Requested qty(' . $qty . ') not available in stock';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated Successfuly';
            $status = true;
            session()->flash('success', $message);
        }




        session()->flash('success', $message);
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }


    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);

        $errMessage = 'Item not found in car';
        if ($itemInfo == null) {
            session()->flash('error', $errMessage);
            return response()->json([
                'status' => false,
                'message' => $errMessage
            ]);
        }
        $message = 'Item removed from cart successfully';
        Cart::remove($request->rowId);
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }


    // CheckOut
    public function checkout(){
        if(Cart::count()==0){
            return redirect()->route('front.cart');
        }

        // if user is not logged in then he /she will redurect to login page
        if(Auth::check()==false){
            if(!session()->has('url.intended')){
                session(['url.intended'=>url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress=CustomerAddress::where('user_id',Auth::user()->id)->first();
        session()->forget('url.intended');
        $countries=Country::orderBy('name','ASC')->get();

        // Calculate shipping Chage
        if($customerAddress !=''){

            $userCountry=$customerAddress->country_id;
       
            $ShippingChargeInfo=ShippingCharge::where('country_id',$userCountry)->first();
            $totalQty=0;
            $totalShippingCharge=0;
            $grandTotal=0;
            foreach (Cart::content() as $item) {
                $totalQty+=$item->qty;
            }
            $totalShippingCharge=$totalQty*$ShippingChargeInfo->amount;
            $grandTotal=Cart::subtotal(2,'.','')+$totalShippingCharge;
        }else{
            $grandTotal=Cart::subtotal(2,'.','');
            $totalShippingCharge=0;
        }

        return view('front.checkout',[
            'countries'=>$countries,
            'customerAddress'=>$customerAddress,
            'totalShippingCharge'=>$totalShippingCharge,
            'grandTotal'=>$grandTotal
        ]);
    }


    public function processCheckout(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'address' => 'required|min:8',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        // Store data in customer_addresses table
        $user = Auth::user();
        $customerAddress = CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'mobile' => $request->mobile,
            ]
        );
    
        // Process order if payment method is 'cod'
        if ($request->payment_method == 'cod') {
            // Calculate subtotal
            $subTotal = Cart::subtotal(2, '.', '');
    
            // Calculate shipping charge based on country
            $shippingCharge = 0; // Default shipping charge is 0
    
            if ($request->country > 0) {
                // Get shipping information based on country
                $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();
    
                // Calculate total quantity of items in cart
                $totalQty = 0;
                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }
    
                // If shipping info is found, calculate the shipping charge
                if ($shippingInfo != null) {
                    $shippingCharge = $totalQty * $shippingInfo->amount;
                } else {
                    // Default to 'rest_of_world' rate if no specific rate is found
                    $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                    if ($shippingInfo != null) {
                        $shippingCharge = $totalQty * $shippingInfo->amount;
                    }
                }
            }
    
            // Calculate the grand total (subtotal + shipping charge)
            $grandTotal = $subTotal + $shippingCharge;
    
            // Create and save the order
            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shippingCharge;
            $order->grand_total = $grandTotal;
    
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->country_id = $request->country;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->notes = $request->order_notes;
            $order->zip = $request->zip;
            $order->save();
    
            // Store data in order_items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }
    
            // Clear the cart and return response
            session()->flash('success', 'You have successfully placed your order');
            Cart::destroy();
            return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Order saved successfully'
            ]);
    
        } else {
            // Process other payment methods here
        }
    }
    


    public function thankyou($id){
        return view('front.thanks',[
            'id'=>$id
        ]); 
    }

    public function getOrderSummery(Request $request)
    {
        // Get subtotal from the cart
        $subTotal = Cart::subtotal(2, '.', '');
    
        // Initialize shippingCharge as 0
        $shippingCharge = 0;
    
        // Check if country_id is provided and greater than 0
        if ($request->country_id > 0) {
            // Get the shipping info for the selected country
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
    
            // Calculate total quantity of items in the cart
            $totalQty = Cart::count(); // Updated to use count() for total quantity
    
            // If specific shipping info for the country is found, use it
            if ($shippingInfo !== null) {
                $shippingCharge = $totalQty * $shippingInfo->amount;
            } else {
                // If no specific shipping charge, default to 'rest_of_world' rate
                $restOfWorldShipping = ShippingCharge::where('country_id', 'rest_of_world')->first();
                if ($restOfWorldShipping !== null) {
                    $shippingCharge = $totalQty * $restOfWorldShipping->amount;
                }
            }
        }
    
        // Calculate grand total
        $grandTotal = $subTotal + $shippingCharge;
    
        // Return response with calculated shipping and grand total
        return response()->json([
            'status' => true,
            'shippingCharge' => number_format($shippingCharge, 2),
            'grandTotal' => number_format($grandTotal, 2)
        ]);
    }
    
    
    
    
    
    
    

   
    
    
}
