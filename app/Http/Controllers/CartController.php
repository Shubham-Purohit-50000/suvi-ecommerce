<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Country;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;
use App\Models\BusinessSetting;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }
        if (count($carts) > 0) {
            $carts->toQuery()->update(['shipping_cost' => 0]);
            $carts = $carts->fresh();
        }

        $is_special_subscribed = false;
        $special_discount = 0;
        if (auth()->user()) {
            $user = Auth::user();
            $active_special_subscription = $user->specialSubscriptions()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with('specialSubscription')
                ->latest('end_date')
                ->first();
            if ($active_special_subscription && $active_special_subscription->specialSubscription) {
                $is_special_subscribed = true;
                $special_discount = $active_special_subscription->specialSubscription->discount;
            }
        }

        $general_discount_amount = 0;
        $general_discount_message = null;
        $general_discount = BusinessSetting::where('type', 'general_discount')->first();
        if ($general_discount) {
            $general_discount_data = json_decode($general_discount->value, true);
            if (isset($general_discount_data['active']) && $general_discount_data['active'] == 1 && isset($general_discount_data['percentage']) && $general_discount_data['percentage'] > 0) {
                $unique_products = $carts->pluck('product_id')->unique()->count();
                if ($unique_products >= 2) {
                    $cart_subtotal = 0;
                    foreach ($carts as $cartItem) {
                        $product = Product::find($cartItem['product_id']);
                        $cart_subtotal += CartUtility::get_price($product, $product->stocks->where('variant', $cartItem['variation'])->first(), $cartItem['quantity']) * $cartItem['quantity'];
                    }
                    $general_discount_amount = ($cart_subtotal * $general_discount_data['percentage']) / 100;
                    $general_discount_message = __('You have received a General Discount of :percent%', ['percent' => $general_discount_data['percentage']]);
                }
            }
        }

        return view('frontend.view_cart', compact('carts', 'is_special_subscribed', 'special_discount', 'general_discount_amount', 'general_discount_message'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.cart.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $authUser = auth()->user();
        if($authUser != null) {
            $user_id = $authUser->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::find($request->id);
        $carts = array();

        if($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.cart.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());
        $product_stock = $product->stocks->where('variant', $str)->first();

        if($authUser != null) {
            $user_id = $authUser->id;
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'user_id' => $user_id,
                'product_id' => $request['id']
            ]);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'temp_user_id' => $temp_user_id,
                'product_id' => $request['id']
            ]);
        }

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.cart.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

        if($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.partials.cart.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        $authUser = auth()->user();
        if ($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        // Special Discount
        $is_special_subscribed = false;
        $special_discount = 0;
        if (auth()->user()) {
            $user = Auth::user();
            $active_special_subscription = $user->specialSubscriptions()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with('specialSubscription')
                ->latest('end_date')
                ->first();
            if ($active_special_subscription && $active_special_subscription->specialSubscription) {
                $is_special_subscribed = true;
                $special_discount = $active_special_subscription->specialSubscription->discount;
            }
        }
        // General Discount
        $general_discount_amount = 0;
        $general_discount_message = null;
        $general_discount = BusinessSetting::where('type', 'general_discount')->first();
        if ($general_discount) {
            $general_discount_data = json_decode($general_discount->value, true);
            if (isset($general_discount_data['active']) && $general_discount_data['active'] == 1 && isset($general_discount_data['percentage']) && $general_discount_data['percentage'] > 0) {
                $unique_products = $carts->pluck('product_id')->unique()->count();
                if ($unique_products >= 2) {
                    $cart_subtotal = 0;
                    foreach ($carts as $cartItem) {
                        $product = Product::find($cartItem['product_id']);
                        $cart_subtotal += CartUtility::get_price($product, $product->stocks->where('variant', $cartItem['variation'])->first(), $cartItem['quantity']) * $cartItem['quantity'];
                    }
                    $general_discount_amount = ($cart_subtotal * $general_discount_data['percentage']) / 100;
                    $general_discount_message = __('You have received a General Discount of :percent%', ['percent' => $general_discount_data['percentage']]);
                }
            }
        }
        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', [
                'carts' => $carts,
                'is_special_subscribed' => $is_special_subscribed,
                'special_discount' => $special_discount,
                'general_discount_amount' => $general_discount_amount,
                'general_discount_message' => $general_discount_message
            ])->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $quantity = $product_stock->qty;
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        // Special Discount
        $is_special_subscribed = false;
        $special_discount = 0;
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $user = Auth::user();
            $active_special_subscription = $user->specialSubscriptions()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with('specialSubscription')
                ->latest('end_date')
                ->first();
            if ($active_special_subscription && $active_special_subscription->specialSubscription) {
                $is_special_subscribed = true;
                $special_discount = $active_special_subscription->specialSubscription->discount;
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        // General Discount
        $general_discount_amount = 0;
        $general_discount_message = null;
        $general_discount = BusinessSetting::where('type', 'general_discount')->first();
        if ($general_discount) {
            $general_discount_data = json_decode($general_discount->value, true);
            if (isset($general_discount_data['active']) && $general_discount_data['active'] == 1 && isset($general_discount_data['percentage']) && $general_discount_data['percentage'] > 0) {
                $unique_products = $carts->pluck('product_id')->unique()->count();
                if ($unique_products >= 2) {
                    $cart_subtotal = 0;
                    foreach ($carts as $cartItem) {
                        $product = Product::find($cartItem['product_id']);
                        $cart_subtotal += CartUtility::get_price($product, $product->stocks->where('variant', $cartItem['variation'])->first(), $cartItem['quantity']) * $cartItem['quantity'];
                    }
                    $general_discount_amount = ($cart_subtotal * $general_discount_data['percentage']) / 100;
                    $general_discount_message = __('You have received a General Discount of :percent%', ['percent' => $general_discount_data['percentage']]);
                }
            }
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', [
                'carts' => $carts,
                'is_special_subscribed' => $is_special_subscribed,
                'special_discount' => $special_discount,
                'general_discount_amount' => $general_discount_amount,
                'general_discount_message' => $general_discount_message
            ])->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    public function updateCartStatus(Request $request)
    {
        $product_ids = $request->product_id;

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $coupon_applied = $carts->toQuery()->where('coupon_applied', 1)->first();
        if($coupon_applied != null){
            $owner_id = $coupon_applied->owner_id;
            $coupon_code = $coupon_applied->coupon_code;
            $user_carts = $carts->toQuery()->where('owner_id', $owner_id)->get();
            $coupon_discount = $user_carts->toQuery()->sum('discount');
            $user_carts->toQuery()->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
        }

        $carts->toQuery()->update(['status' => 0]);
        if($product_ids != null){
            if($coupon_applied != null){
                $active_user_carts = $user_carts->toQuery()->whereIn('product_id', $product_ids)->get();
                if (count($active_user_carts) > 0) {
                    $active_user_carts->toQuery()->update(
                        [
                            'discount' => $coupon_discount / count($active_user_carts),
                            'coupon_code' => $coupon_code,
                            'coupon_applied' => 1
                        ]
                    );
                }
            }

            $carts->toQuery()->whereIn('product_id', $product_ids)->update(['status' => 1]);
        }
        $carts = $carts->fresh();

        // Special Discount
        $is_special_subscribed = false;
        $special_discount = 0;
        if (auth()->user()) {
            $user = Auth::user();
            $active_special_subscription = $user->specialSubscriptions()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with('specialSubscription')
                ->latest('end_date')
                ->first();
            if ($active_special_subscription && $active_special_subscription->specialSubscription) {
                $is_special_subscribed = true;
                $special_discount = $active_special_subscription->specialSubscription->discount;
            }
        }
        // General Discount
        $general_discount_amount = 0;
        $general_discount_message = null;
        $general_discount = BusinessSetting::where('type', 'general_discount')->first();
        if ($general_discount) {
            $general_discount_data = json_decode($general_discount->value, true);
            if (isset($general_discount_data['active']) && $general_discount_data['active'] == 1 && isset($general_discount_data['percentage']) && $general_discount_data['percentage'] > 0) {
                $unique_products = $carts->pluck('product_id')->unique()->count();
                if ($unique_products >= 2) {
                    $cart_subtotal = 0;
                    foreach ($carts as $cartItem) {
                        $product = Product::find($cartItem['product_id']);
                        $cart_subtotal += CartUtility::get_price($product, $product->stocks->where('variant', $cartItem['variation'])->first(), $cartItem['quantity']) * $cartItem['quantity'];
                    }
                    $general_discount_amount = ($cart_subtotal * $general_discount_data['percentage']) / 100;
                    $general_discount_message = __('You have received a General Discount of :percent%', ['percent' => $general_discount_data['percentage']]);
                }
            }
        }
        return view('frontend.partials.cart.cart_details', [
            'carts' => $carts,
            'is_special_subscribed' => $is_special_subscribed,
            'special_discount' => $special_discount,
            'general_discount_amount' => $general_discount_amount,
            'general_discount_message' => $general_discount_message
        ])->render();
    }
}
