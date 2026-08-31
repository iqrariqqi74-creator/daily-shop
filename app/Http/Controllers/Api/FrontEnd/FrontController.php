<?php

namespace App\Http\Controllers\Api\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{


    public function index(Request $request)
    {
        $result = [];

        // =========================
        // CATEGORIES
        // =========================
        $categories = DB::table('categories')
            ->where('status', 'active')
            ->whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get();

        foreach ($categories as $category) {

            $category->children = DB::table('categories')
                ->where('status', 'active')
                ->where('parent_id', $category->id)
                ->orderBy('name', 'asc')
                ->get();
        }

        $result['home_categories'] = $categories;


        // =========================
        // CATEGORY PRODUCTS
        // =========================
        $result['home_categories_product'] = [];
        $result['home_product_attr'] = [];

        foreach ($categories as $category) {

            $products = DB::table('products')
                ->where('status', 'active')
                ->where('category_id', $category->id)
                ->get();

            $result['home_categories_product'][$category->id] = $products;

            foreach ($products as $product) {

                $result['home_product_attr'][$product->id] =
                    DB::table('product_attributes')
                    ->leftJoin(
                        'sizes',
                        'sizes.id',
                        '=',
                        'product_attributes.size_id'
                    )
                    ->leftJoin(
                        'colors',
                        'colors.id',
                        '=',
                        'product_attributes.color_id'
                    )
                    ->where(
                        'product_attributes.product_id',
                        $product->id
                    )
                    ->select(
                        'product_attributes.*',
                        'sizes.name as size_name',
                        'colors.name as color_name'
                    )
                    ->get();
            }
        }


        // =========================
        // BRANDS
        // =========================
        $result['home_brand'] = DB::table('brands')
            ->where('status', 'active')
            ->get();


        // =========================
        // FEATURED PRODUCTS
        // =========================
        $result['home_featured_product'] = DB::table('products')
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->get();

        $result['home_featured_product_attr'] = [];

        foreach ($result['home_featured_product'] as $product) {

            $result['home_featured_product_attr'][$product->id] =
                DB::table('product_attributes')
                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )
                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )
                ->where(
                    'product_attributes.product_id',
                    $product->id
                )
                ->select(
                    'product_attributes.*',
                    'sizes.name as size_name',
                    'colors.name as color_name'
                )
                ->get();
        }


        // =========================
        // TRENDING PRODUCTS
        // =========================
        $result['home_tranding_product'] = DB::table('products')
            ->where('status', 'active')
            ->where('is_tranding', 1)
            ->get();

        $result['home_tranding_product_attr'] = [];

        foreach ($result['home_tranding_product'] as $product) {

            $result['home_tranding_product_attr'][$product->id] =
                DB::table('product_attributes')
                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )
                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )
                ->where(
                    'product_attributes.product_id',
                    $product->id
                )
                ->select(
                    'product_attributes.*',
                    'sizes.name as size_name',
                    'colors.name as color_name'
                )
                ->get();
        }


        // =========================
        // DISCOUNTED PRODUCTS
        // =========================
        $result['home_discounted_product'] = DB::table('products')
            ->where('status', 'active')
            ->where('is_discounted', 1)
            ->get();

        $result['home_discounted_product_attr'] = [];

        foreach ($result['home_discounted_product'] as $product) {

            $result['home_discounted_product_attr'][$product->id] =
                DB::table('product_attributes')
                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )
                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )
                ->where(
                    'product_attributes.product_id',
                    $product->id
                )
                ->select(
                    'product_attributes.*',
                    'sizes.name as size_name',
                    'colors.name as color_name'
                )
                ->get();
        }


        // =========================
        // BANNERS
        // =========================
        $result['home_banner'] = DB::table('banners')
            ->where('status', 'active')
            ->get();


        // =========================
        // RESPONSE
        // =========================
        return response()->json([
            'status' => 'success',
            'result' => $result,
        ]);
    }

    public function category(Request $request, $id)
    {
        // =========================================================
        // DEFAULT VALUES
        // =========================================================

        $sort = "";
        $sort_txt = "";

        $filter_price_start = "";
        $filter_price_end = "";

        $color_filter = "";
        $colorFilterArr = [];

        // =========================================================
        // SORT
        // =========================================================

        if ($request->get('sort') !== null) {
            $sort = $request->get('sort');
        }

        // =========================================================
        // MAIN PRODUCT QUERY
        // =========================================================

        $query = DB::table('products');

        $query->leftJoin(
            'categories',
            'categories.id',
            '=',
            'products.category_id'
        );

        $query->leftJoin(
            'product_attributes',
            'products.id',
            '=',
            'product_attributes.product_id'
        );

        // =========================================================
        // ACTIVE PRODUCTS
        // =========================================================

        $query->where(
            'products.status',
            1
        );

        // =========================================================
        // CATEGORY BY ID
        // =========================================================

        $query->where(
            'categories.id',
            $id
        );

        // =========================================================
        // SORT
        // =========================================================

        if ($sort == 'name') {

            $query->orderBy(
                'products.name',
                'asc'
            );

            $sort_txt = "Product Name";
        }

        if ($sort == 'date') {

            $query->orderBy(
                'products.id',
                'desc'
            );

            $sort_txt = "Date";
        }

        if ($sort == 'price_desc') {

            $query->orderBy(
                'product_attributes.price',
                'desc'
            );

            $sort_txt = "Price - DESC";
        }

        if ($sort == 'price_asc') {

            $query->orderBy(
                'product_attributes.price',
                'asc'
            );

            $sort_txt = "Price - ASC";
        }

        // =========================================================
        // PRICE FILTER
        // =========================================================

        if (
            $request->get('filter_price_start') !== null &&
            $request->get('filter_price_end') !== null
        ) {

            $filter_price_start =
                $request->get('filter_price_start');

            $filter_price_end =
                $request->get('filter_price_end');

            if (
                $filter_price_start > 0 &&
                $filter_price_end > 0
            ) {

                $query->whereBetween(
                    'product_attributes.price',
                    [
                        $filter_price_start,
                        $filter_price_end
                    ]
                );
            }
        }

        // =========================================================
        // COLOR FILTER
        // =========================================================

        if (
            $request->get('color_filter') !== null &&
            $request->get('color_filter') !== ''
        ) {

            $color_filter =
                $request->get('color_filter');

            // Example:
            // 1:2:3

            $colorFilterArr =
                explode(':', $color_filter);

            $colorFilterArr =
                array_filter($colorFilterArr);

            if (!empty($colorFilterArr)) {

                $query->whereIn(
                    'product_attributes.color_id',
                    $colorFilterArr
                );
            }
        }

        // =========================================================
        // GET PRODUCTS
        // =========================================================

        $result = [];

        $result['products'] = $query
            ->distinct()
            ->select('products.*')
            ->get();

        // =========================================================
        // PRODUCT ATTRIBUTES
        //
        // IMPORTANT:
        // sizes.size aur colors.color hata diye hain
        // kyunki tumhare sizes table mein "size" column nahi hai.
        // =========================================================

        $result['product_attributes'] = [];

        foreach ($result['products'] as $product) {

            $attributes = DB::table(
                'product_attributes'
            )
                ->where(
                    'product_attributes.product_id',
                    $product->id
                )
                ->get();

            $result['product_attributes'][$product->id] =
                $attributes;
        }

        // =========================================================
        // ACTIVE COLORS
        // =========================================================

        $result['colors'] = DB::table('colors')
            ->where(
                'status',
                'active'
            )
            ->get();

        // =========================================================
        // ACTIVE CATEGORIES
        // =========================================================

        $result['categories_left'] =
            DB::table('categories')
            ->where(
                'status',
                'active'
            )
            ->get();

        // =========================================================
        // CATEGORY ID
        // =========================================================

        $result['id'] = $id;

        // =========================================================
        // SORT DATA
        // =========================================================

        $result['sort'] = $sort;

        $result['sort_txt'] = $sort_txt;

        // =========================================================
        // PRICE DATA
        // =========================================================

        $result['filter_price_start'] =
            $filter_price_start;

        $result['filter_price_end'] =
            $filter_price_end;

        // =========================================================
        // COLOR DATA
        // =========================================================

        $result['color_filter'] =
            $color_filter;

        $result['colorFilterArr'] =
            array_values($colorFilterArr);

        // =========================================================
        // API RESPONSE
        // =========================================================

        return response()->json([
            'success' => true,
            'result' => $result,
        ]);
    }

    public function product(Request $request, $slug)
    {
        try {

            // =====================================================
            // PRODUCT
            // =====================================================

            $result['product'] = DB::table('products')
                ->where('status', 'active')
                ->where('slug', $slug)
                ->get();

            if ($result['product']->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
                ], 404);
            }


            // =====================================================
            // PRODUCT ATTRIBUTES
            // =====================================================

            foreach ($result['product'] as $list1) {

                $result['product_attr'][$list1->id] =
                    DB::table('product_attributes')
                    ->leftJoin(
                        'sizes',
                        'sizes.id',
                        '=',
                        'product_attributes.size_id'
                    )
                    ->leftJoin(
                        'colors',
                        'colors.id',
                        '=',
                        'product_attributes.color_id'
                    )
                    ->where(
                        'product_attributes.product_id',
                        $list1->id
                    )
                    ->select(
                        'product_attributes.*',
                        'sizes.name as size',
                        'colors.name as color'
                    )
                    ->get();
            }


            // =====================================================
            // PRODUCT IMAGES
            // =====================================================

            foreach ($result['product'] as $list1) {

                $result['product_images'][$list1->id] =
                    DB::table('product_images')
                    ->where(
                        'product_images.product_id',
                        $list1->id
                    )
                    ->get();
            }


            // =====================================================
            // RELATED PRODUCTS
            // =====================================================

            $result['related_product'] =
                DB::table('products')
                ->where('status', 'active')
                ->where('slug', '!=', $slug)
                ->where(
                    'category_id',
                    $result['product'][0]->category_id
                )
                ->get();


            // =====================================================
            // RELATED PRODUCT ATTRIBUTES
            // =====================================================

            foreach ($result['related_product'] as $list1) {

                $result['related_product_attr'][$list1->id] =
                    DB::table('product_attributes')
                    ->leftJoin(
                        'sizes',
                        'sizes.id',
                        '=',
                        'product_attributes.size_id'
                    )
                    ->leftJoin(
                        'colors',
                        'colors.id',
                        '=',
                        'product_attributes.color_id'
                    )
                    ->where(
                        'product_attributes.product_id',
                        $list1->id
                    )
                    ->select(
                        'product_attributes.*',
                        'sizes.name as size',
                        'colors.name as color'
                    )
                    ->get();
            }


            // =====================================================
            // REVIEWS
            // =====================================================

            $result['product_review'] =
                DB::table('product_reviews')
                ->leftJoin(
                    'customers',
                    'customers.id',
                    '=',
                    'product_reviews.customer_id'
                )
                ->where(
                    'product_reviews.product_id',
                    $result['product'][0]->id
                )
                ->where(
                    'product_reviews.status',
                    1
                )
                ->orderBy(
                    'product_reviews.added_on',
                    'desc'
                )
                ->select(
                    'product_reviews.rating',
                    'product_reviews.review',
                    'product_reviews.added_on',
                    'customers.name'
                )
                ->get();


            // =====================================================
            // SUCCESS
            // =====================================================

            return response()->json([
                'status' => true,
                'message' => 'Product loaded successfully.',
                'result' => $result,
            ]);
        } catch (\Throwable $e) {

            \Log::error('PRODUCT API ERROR', [
                'slug' => $slug,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while loading product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function add_to_cart(Request $request)
    {
        try {
            // =====================================================
            // VALIDATION
            // =====================================================

            $request->validate([
                'product_id' => 'required|integer',
                'size_id'    => 'nullable|integer',
                'color_id'   => 'nullable|integer',
                // 'quantity'   => 'required|integer|min:1',
            ]);


            // =====================================================
            // USER / GUEST
            // =====================================================

            $user = auth('customer_api')->user();

            if ($user) {

                // LOGIN USER
                $uid = $user->id;
                $user_type = "Reg";
            } else {

                // GUEST USER
                $guestId = $request->header('X-Guest-ID');

                if (!$guestId) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'guest_id_required',
                        'data'    => 'Guest ID is required.'
                    ], 422);
                }

                $uid = $guestId;
                $user_type = "Not-Reg";
            }


            // =====================================================
            // PRODUCT ATTRIBUTE
            // =====================================================

            $attribute = DB::table('product_attributes')
                ->where('product_id', $request->product_id)
                ->when(
                    $request->size_id !== null,
                    function ($query) use ($request) {
                        $query->where('size_id', $request->size_id);
                    }
                )
                ->when(
                    $request->color_id !== null,
                    function ($query) use ($request) {
                        $query->where('color_id', $request->color_id);
                    }
                )
                ->first();


            if (!$attribute) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Product attribute not found.'
                ], 404);
            }


            // =====================================================
            // CHECK EXISTING CART ITEM
            // =====================================================

            $cart = DB::table('carts')
                ->where('user_id', $uid)
                ->where('user_type', $user_type)
                ->where('product_id', $request->product_id)
                ->where('product_attribute_id', $attribute->id)
                ->first();


            // =====================================================
            // EXISTING PRODUCT
            // =====================================================

            if ($cart) {

                // DELETE
                if ((int) $request->quantity === 0) {

                    DB::table('carts')
                        ->where('id', $cart->id)
                        ->delete();

                    return response()->json([
                        'status'   => true,
                        'message'  => 'removed',
                        'cart_id'  => $cart->id,
                        'quantity' => 0,
                    ]);
                }

                // UPDATE QUANTITY
                $newQuantity =
                    (int) $cart->quantity +
                    (int) $request->quantity;

                DB::table('carts')
                    ->where('id', $cart->id)
                    ->update([
                        'quantity'   => $newQuantity,
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'status'   => true,
                    'message'  => 'Cart quantity updated successfully.',
                    'cart_id'  => $cart->id,
                    'quantity' => $newQuantity,
                ]);
            }
            // =====================================================
            // NEW PRODUCT
            // =====================================================

            $cartId = DB::table('carts')->insertGetId([

                'user_id' => $uid,

                'user_type' => $user_type,

                'product_id' => $request->product_id,

                'product_attribute_id' => $attribute->id,

                'quantity' => $request->quantity,

                'created_at' => now(),

                'updated_at' => now(),

            ]);


            return response()->json([
                'status'   => true,
                'message'  => 'Product added to cart successfully.',
                'cart_id'  => $cartId,
                'quantity' => $request->quantity,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unable to add product to cart.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function cart(Request $request)
    {
        try {

            $user = auth('customer_api')->user();

            if ($user) {
                // LOGIN USER
                $uid = $user->id;
                $user_type = "Reg";
            } else {
                // GUEST USER
                $uid = $request->header('X-Guest-ID');
                $user_type = "Not-Reg";
            }

            // ==========================================
            // CHECK USER / GUEST ID
            // ==========================================

            if (!$uid) {

                return response()->json([
                    'status' => false,
                    'message' => 'Guest ID not found.',
                    'result' => [
                        'list' => []
                    ]
                ], 400);
            }

            // ==========================================
            // CART DATA
            // ==========================================

            $list = DB::table('carts')

                ->leftJoin(
                    'products',
                    'products.id',
                    '=',
                    'carts.product_id'
                )

                ->leftJoin(
                    'product_attributes',
                    'product_attributes.id',
                    '=',
                    'carts.product_attribute_id'
                )
                // SIZE JOIN
                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )

                // COLOR JOIN
                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )

                ->where('carts.user_id', $uid)

                ->where('carts.user_type', $user_type)

                ->select(

                    // CART
                    'carts.id as cart_id',
                    'carts.quantity',

                    // PRODUCT
                    'products.id as pid',
                    'products.name',
                    'products.image',
                    'products.slug',

                    // ATTRIBUTE
                    'product_attributes.id as attr_id',
                    'product_attributes.size_id',
                    'product_attributes.color_id',
                    'product_attributes.price',
                    'product_attributes.attribute_image',

                    // ==========================================
                    // SIZE NAME
                    // ==========================================
                    'sizes.name as size',

                    // ==========================================
                    // COLOR NAME
                    // ==========================================
                    'colors.name as color'

                )

                ->get();

            // ==========================================
            // FORMAT CART
            // ==========================================

            $list = $list->map(function ($item) {

                return [
                    'cart_id' => $item->cart_id,

                    'pid' => $item->pid,

                    'name' => $item->name,

                    'image' => $item->image,

                    'slug' => $item->slug,

                    'attr_id' => $item->attr_id,

                    'size_id' => $item->size_id,

                    'color_id' => $item->color_id,

                    // ATTRIBUTE NAMES
                    'size' => $item->size,

                    'color' => $item->color,

                    'price' => $item->price,

                    'qty' => $item->quantity,

                    'attribute_image' => $item->attribute_image,

                    'total' => ((float) $item->price * (int) $item->quantity),
                ];
            });

            // ==========================================
            // RESPONSE
            // ==========================================

            return response()->json([

                'status' => true,

                'message' => 'Cart data fetched successfully.',

                'result' => [

                    'list' => $list

                ]

            ]);
        } catch (\Exception $e) {

            // ==========================================
            // ERROR
            // ==========================================

            return response()->json([

                'status' => false,

                'message' => 'Unable to fetch cart.',

                'error' => $e->getMessage(),

                'result' => [

                    'list' => []

                ]

            ], 500);
        }
    }

    public function search(Request $request)
    {
        $str = trim($request->search);

        if (!$str) {
            return response()->json([
                'products' => [],
                'product_attr' => [],
            ]);
        }

        $products = DB::table('products')
            ->where('products.status', 1)
            ->where(function ($q) use ($str) {
                $q->where('products.name', 'like', "%{$str}%")
                    ->orWhere('products.model', 'like', "%{$str}%")
                    ->orWhere('products.short_description', 'like', "%{$str}%")
                    ->orWhere('products.description', 'like', "%{$str}%")
                    ->orWhere('products.keywords', 'like', "%{$str}%")
                    ->orWhere(
                        'products.technical_specification',
                        'like',
                        "%{$str}%"
                    );
            })
            ->select('products.*')
            ->distinct()
            ->get();

        $productAttr = [];

        foreach ($products as $product) {
            $productAttr[$product->id] = DB::table('product_attributes')
                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )
                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )
                ->where(
                    'product_attributes.product_id',
                    $product->id
                )
                ->select(
                    'product_attributes.*',
                    'sizes.name as size_name',
                    'colors.name as color_name'
                )
                ->get();
        }

        return response()->json([
            'products' => $products,
            'product_attr' => $productAttr,
        ]);
    }

    public function registration_process(Request $request)
    {
        // =====================================================
        // VALIDATION
        // =====================================================

        $valid = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|numeric|digits:10',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please check the entered information.',
                'errors' => $valid->errors(),
            ], 422);
        }

        // =====================================================
        // RANDOM VERIFICATION ID
        // =====================================================

        $rand_id = rand(111111111, 999999999);

        // =====================================================
        // CUSTOMER DATA
        // =====================================================

        $arr = [
            'name' => $request->name,
            'email' => $request->email,

            // IMPORTANT: Hash password
            'password' => Hash::make($request->password),

            'phone_number' => $request->phone_number,

            'status' => 1,

            // Email verification pending
            'is_valid' => 0,

            'rand_id' => $rand_id,

            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =====================================================
        // CREATE CUSTOMER
        // =====================================================

        $customerId = DB::table('customers')->insertGetId($arr);

        if (!$customerId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.',
            ], 500);
        }

        // =====================================================
        // SEND VERIFICATION EMAIL
        // =====================================================

        try {

            $data = [
                'name' => $request->name,
                'rand_id' => $rand_id,
            ];

            Mail::send(
                'front.email_verification',
                $data,
                function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Email Verification');
                }
            );
        } catch (\Exception $e) {

            Log::error(
                'Registration verification email failed: ' .
                    $e->getMessage()
            );
        }

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successfully. Please check your email id for verification.',
        ], 201);
    }

    public function login_process(Request $request)
    {
        // =====================================================
        // VALIDATION
        // =====================================================

        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter valid login details.',
                'errors' => $valid->errors(),
            ], 422);
        }

        // =====================================================
        // FIND CUSTOMER
        // =====================================================

        $user = DB::table('customers')
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter valid email id.',
            ], 401);
        }

        // =====================================================
        // EMAIL VERIFICATION
        // =====================================================

        if ((int) $user->is_valid === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please verify your email id.',
            ], 403);
        }

        // =====================================================
        // ACCOUNT STATUS
        // =====================================================

        if ((int) $user->status === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account has been deactivated.',
            ], 403);
        }

        // =====================================================
        // PASSWORD
        // =====================================================

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter valid password.',
            ], 401);
        }

        // =====================================================
        // GET CUSTOMER MODEL
        // =====================================================

        $customer = \App\Models\Customer::find($user->id);

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer account not found.',
            ], 404);
        }

        // =====================================================
        // PASSPORT TOKEN
        // =====================================================

        $tokenResult = $customer->createToken('Frontend Token');

        $token = $tokenResult->accessToken;

        // =====================================================
        // GUEST CART -> CUSTOMER CART
        // =====================================================

        $guestId = $request->header('X-Guest-ID');

        if ($guestId) {

            DB::table('carts')
                ->where('user_id', $guestId)
                ->where('user_type', 'Not-Reg')
                ->update([
                    'user_id' => $customer->id,
                    'user_type' => 'Reg',
                ]);
        }

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([
            'status' => 'success',
            'message' => 'Login successfully.',

            'token' => $token,

            'user' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone_number' => $customer->phone_number,
            ],
        ], 200);
    }

    public function email_verification($rand_id)
    {
        $user = DB::table('customers')
            ->where('rand_id', $rand_id)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid verification link.'
            ], 404);
        }

        if ((int) $user->is_valid === 1) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email is already verified.'
            ]);
        }

        DB::table('customers')
            ->where('id', $user->id)
            ->update([
                'is_valid' => 1,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.'
        ]);
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $result = DB::table('customers')
            ->where('email', $request->email)
            ->first();

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email id not registered'
            ], 404);
        }

        $rand_id = rand(111111111, 999999999);

        DB::table('customers')
            ->where('email', $request->email)
            ->update([
                'is_forgot_password' => 1,
                'rand_id' => $rand_id
            ]);

        $data = [
            'name' => $result->name,
            'rand_id' => $rand_id,
        ];

        try {

            Mail::send(
                'front.forgot_email',
                $data,
                function ($message) use ($request) {

                    $message->to($request->email);
                    $message->subject('Forgot Password');
                }
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Please check your email for password reset code.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Email could not be sent.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'rand_id' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        // Customer find
        $customer = DB::table('customers')
            ->where('rand_id', $request->rand_id)
            ->where('is_forgot_password', 1)
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired password reset link.'
            ], 400);
        }

        // Update password
        DB::table('customers')
            ->where('id', $customer->id)
            ->update([
                'password' => Hash::make($request->password),
                'is_forgot_password' => 0,
                'rand_id' => null,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.'
        ]);
    }

    public function logout(Request $request)
    {
        try {

            // ==========================================
            // GET AUTHENTICATED CUSTOMER
            // ==========================================

            $customer = $request->user('customer_api');

            if (!$customer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            // ==========================================
            // REVOKE CURRENT PASSPORT TOKEN
            // ==========================================

            $token = $customer->token();

            if ($token) {
                $token->revoke();
            }

            // ==========================================
            // RESPONSE
            // ==========================================

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successfully.',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function checkout(Request $request)
    {
        $result['cart_data'] = getAddToCartTotalItem();

        if (isset($result['cart_data'][0])) {

            if ($request->session()->has('FRONT_USER_LOGIN')) {
                $uid = $request->session()->get('FRONT_user_id');
                $customer_info = DB::table('customers')
                    ->where(['id' => $uid])
                    ->get();
                $result['customers']['name'] = $customer_info[0]->name;
                $result['customers']['email'] = $customer_info[0]->email;
                $result['customers']['phone_number'] = $customer_info[0]->phone_number;
                $result['customers']['address'] = $customer_info[0]->address;
                $result['customers']['city'] = $customer_info[0]->city;
                $result['customers']['state'] = $customer_info[0]->state;
                $result['customers']['zip'] = $customer_info[0]->zip;
            } else {
                $result['customers']['name'] = '';
                $result['customers']['email'] = '';
                $result['customers']['phone_number'] = '';
                $result['customers']['address'] = '';
                $result['customers']['city'] = '';
                $result['customers']['state'] = '';
                $result['customers']['zip'] = '';
            }

            return view('front.checkout', $result);
        } else {
            return redirect('/');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        try {

            // =========================================================
            // VALIDATION
            // =========================================================

            $request->validate([
                'coupon_code' => 'required|string',
            ]);

            // =========================================================
            // FIND COUPON
            // =========================================================

            $coupon = DB::table('coupons')
                ->where('code', $request->coupon_code)
                ->first();

            // =========================================================
            // COUPON NOT FOUND
            // =========================================================

            if (!$coupon) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid coupon code.',
                    'totalPrice' => 0,
                    'coupon_code_value' => 0,
                ], 422);
            }

            // =========================================================
            // GET USER
            // =========================================================

            $user = auth('customer_api')->user();

            if ($user) {

                $uid = $user->id;
                $user_type = 'Reg';
            } else {

                $guestId = $request->header('X-Guest-ID');

                if (!$guestId) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Guest ID is required.',
                        'totalPrice' => 0,
                        'coupon_code_value' => 0,
                    ], 422);
                }

                $uid = $guestId;
                $user_type = 'Not-Reg';
            }

            // =========================================================
            // GET CART TOTAL
            // =========================================================

            $cartItems = DB::table('carts')
                ->leftJoin(
                    'product_attributes',
                    'product_attributes.id',
                    '=',
                    'carts.product_attribute_id'
                )
                ->where('carts.user_id', $uid)
                ->where('carts.user_type', $user_type)
                ->select(
                    'carts.quantity',
                    'product_attributes.price'
                )
                ->get();

            // =========================================================
            // CART EMPTY
            // =========================================================

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart is empty.',
                    'totalPrice' => 0,
                    'coupon_code_value' => 0,
                ], 422);
            }

            // =========================================================
            // CALCULATE CART TOTAL
            // =========================================================

            $totalPrice = 0;

            foreach ($cartItems as $item) {

                $totalPrice +=
                    (float) $item->price *
                    (int) $item->quantity;
            }

            // =========================================================
            // COUPON VALUE
            // =========================================================

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Yahan assume kiya gaya hai ke coupons table mein
            | coupon ki discount value `value` column mein hai.
            |
            | Agar tumhare coupons table mein column ka naam
            | `value` nahi hai, mujhe coupons table bhej dena.
            |--------------------------------------------------------------------------
            */

            $couponValue = (float) ($coupon->value ?? 0);

            // =========================================================
            // FINAL TOTAL
            // =========================================================

            $finalTotal = $totalPrice - $couponValue;

            if ($finalTotal < 0) {
                $finalTotal = 0;
            }

            // =========================================================
            // SUCCESS
            // =========================================================

            return response()->json([
                'status' => 'success',
                'message' => 'Coupon code applied successfully.',
                'totalPrice' => $finalTotal,
                'coupon_code_value' => $couponValue,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Coupon code is required.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while applying coupon.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function remove_coupon_code(Request $request)
    {
        $getAddToCartTotalItem = getAddToCartTotalItem();

        $totalPrice = 0;

        foreach ($getAddToCartTotalItem as $list) {

            $totalPrice +=
                ($list->quantity * $list->price);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon code removed',
            'totalPrice' => $totalPrice,
        ]);
    }

    public function place_order(Request $request)
    {
        try {

            // =========================================================
            // VALIDATION
            // =========================================================

            $request->validate([
                'name'         => 'required|string',
                'email'        => 'required|email',
                'mobile'       => 'required|string',
                'address'      => 'required|string',
                'city'         => 'required|string',
                'state'        => 'required|string',
                'zip'          => 'required|string',
                'payment_type' => 'required|in:COD',
            ]);


            // =========================================================
            // LOGGED USER / GUEST
            // =========================================================

            $user = auth('customer_api')->user();

            if ($user) {

                $uid = $user->id;
                $user_type = "Reg";
            } else {

                $guestId = $request->header('X-Guest-ID');

                if (!$guestId) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Guest ID is required.',
                    ], 422);
                }

                $uid = $guestId;
                $user_type = "Not-Reg";
            }


            // =========================================================
            // GET CART
            // =========================================================

            $getAddToCartTotalItem = DB::table('carts')

                ->leftJoin(
                    'products',
                    'products.id',
                    '=',
                    'carts.product_id'
                )

                ->leftJoin(
                    'product_attributes',
                    'product_attributes.id',
                    '=',
                    'carts.product_attribute_id'
                )

                ->where('carts.user_id', $uid)
                ->where('carts.user_type', $user_type)

                ->select(
                    'carts.id as cart_id',
                    'carts.quantity',
                    'carts.product_id as pid',
                    'carts.product_attribute_id as attr_id',
                    'product_attributes.price',
                    'product_attributes.quantity as stock_quantity',
                    'products.tax_id'
                )

                ->get();


            // =========================================================
            // CHECK CART
            // =========================================================

            if ($getAddToCartTotalItem->isEmpty()) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Cart is empty.',
                ], 422);
            }


            // =========================================================
            // CHECK STOCK BEFORE ORDER
            // =========================================================

            foreach ($getAddToCartTotalItem as $list) {

                if ((int) $list->quantity > (int) $list->stock_quantity) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Insufficient stock for product.',
                        'product_id' => $list->pid,
                    ], 422);
                }
            }


            // =========================================================
            // CALCULATE TOTAL
            // =========================================================

            $totalPrice = 0;

            foreach ($getAddToCartTotalItem as $list) {

                $totalPrice +=
                    ((float) $list->price * (int) $list->quantity);
            }


            // =========================================================
            // COUPON
            // =========================================================

            $coupon_value = 0;
            $coupon_code = null;

            if (!empty($request->coupon_code)) {

                $coupon_code = trim($request->coupon_code);

                $coupon = DB::table('coupons')
                    ->where('code', $coupon_code)
                    ->first();

                if (!$coupon) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid coupon code.',
                    ], 422);
                }

                $coupon_value = (float) ($coupon->value ?? 0);
            }

            // =========================================================
            // GET TAX ID
            // =========================================================

            $tax_id = $getAddToCartTotalItem
                ->pluck('tax_id')
                ->filter()
                ->first();

            // =========================================================
            // TAX AMOUNT
            // =========================================================

            $taxAmount = 0;

            if ($tax_id) {
                $tax = DB::table('taxes')
                    ->where('id', $tax_id)
                    ->first();
                $taxAmount = (float) $tax->value ?? 0;
            }

            // =========================================================
            // FINAL TOTAL
            // =========================================================

            $finalAmount = $totalPrice - $coupon_value + $taxAmount;

            if ($finalAmount < 0) {
                $finalAmount = 0;
            }

            // =========================================================
            // VARIABLES FOR EMAIL
            // =========================================================

            $order = null;
            $orderItems = collect();

            DB::transaction(function () use (
                $request,
                $uid,
                $user_type,
                $getAddToCartTotalItem,
                $coupon_value,
                $finalAmount,
                $taxAmount,
                $tax_id,
                &$order,
                &$orderItems
            ) {


                // =====================================================
                // CREATE ORDER
                // =====================================================

                $order_id = DB::table('orders')->insertGetId([
                    'customers_id' => $uid,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pin_code' => $request->zip,
                    'coupon_code' => $request->coupon_code,
                    'coupon_value' => $coupon_value,
                    'payment_type' => 'COD',
                    'payment_status' => 1,
                    'total_amount' => $finalAmount,
                    'order_status' => 1,
                    'tax_id' => $tax_id,
                    'tax_amount' => $taxAmount,
                    'added_on' => now(),
                ]);


                // =====================================================
                // CHECK ORDER CREATED
                // =====================================================

                if (!$order_id) {

                    throw new \Exception(
                        'Unable to create order.'
                    );
                }


                // =====================================================
                // INSERT ORDER DETAILS
                // =====================================================

                foreach ($getAddToCartTotalItem as $list) {

                    DB::table('order_details')->insert([

                        'product_id' =>
                        $list->pid,

                        'product_attribute_id' =>
                        $list->attr_id,

                        'price' =>
                        $list->price,

                        'quantity' =>
                        $list->quantity,

                        'order_id' =>
                        $order_id,
                    ]);


                    // =================================================
                    // DECREASE PRODUCT ATTRIBUTE STOCK
                    // =================================================

                    $updated = DB::table('product_attributes')

                        ->where('id', $list->attr_id)

                        // Important:
                        // stock negative nahi hone dena
                        ->where(
                            'quantity',
                            '>=',
                            (int) $list->quantity
                        )

                        ->decrement(
                            'quantity',
                            (int) $list->quantity
                        );


                    // =================================================
                    // STOCK UPDATE FAILED
                    // =================================================

                    if ($updated === 0) {

                        throw new \Exception(
                            'Insufficient stock for product attribute ID: '
                                . $list->attr_id
                        );
                    }
                }


                // =====================================================
                // DELETE CART
                // =====================================================

                DB::table('carts')

                    ->where('user_id', $uid)

                    ->where('user_type', $user_type)

                    ->delete();


                // =====================================================
                // GET ORDER FOR EMAIL
                // =====================================================

                $order = DB::table('orders')

                    ->where('id', $order_id)

                    ->first();


                // =====================================================
                // GET ORDER ITEMS FOR EMAIL
                // =====================================================

                $orderItems = DB::table('order_details')

                    ->leftJoin(
                        'products',
                        'products.id',
                        '=',
                        'order_details.product_id'
                    )

                    ->leftJoin(
                        'product_attributes',
                        'product_attributes.id',
                        '=',
                        'order_details.product_attribute_id'
                    )

                    ->leftJoin(
                        'sizes',
                        'sizes.id',
                        '=',
                        'product_attributes.size_id'
                    )

                    ->leftJoin(
                        'colors',
                        'colors.id',
                        '=',
                        'product_attributes.color_id'
                    )

                    ->where(
                        'order_details.order_id',
                        $order_id
                    )

                    ->select(
                        'order_details.*',
                        'products.name as product_name',
                        'product_attributes.attribute_image',
                        'sizes.name as size',
                        'colors.name as color'
                    )

                    ->get();
            });

            try {

                Mail::send(
                    'front.order_placed',
                    [
                        'order' => $order,
                        'orderItems' => $orderItems,
                    ],
                    function ($message) use ($order) {
                        $message->to($order->email);
                        $message->subject('#order-' . $order->id . ' Order Placed');
                    }
                );
            } catch (\Exception $mailException) {
                \Log::error(
                    'Order email failed: ' .
                        $mailException->getMessage()
                );
            }


            // =========================================================
            // SUCCESS
            // =========================================================

            return response()->json([

                'status' => true,

                'message' =>
                'Order placed successfully with Cash on Delivery.',

                'payment_type' =>
                'COD',

                'payment_status' =>
                'Pending',

                'order_id' =>
                $order->id,

                'total' =>
                $finalAmount,

            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([

                'status' => false,

                'message' => 'Validation failed.',

                'errors' => $e->errors(),

            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function order_fail(Request $request)
    {
        if ($request->session()->has('ORDER_ID')) {
            return view('front.order_fail');
        } else {
            return redirect('/');
        }
    }

    public function instamojo_payment_redirect(Request $request)
    {
        if ($request->get('payment_id') !== null && $request->get('payment_status') !== null && $request->get('payment_request_id') !== null) {
            if ($request->get('payment_status') == 'Credit') {
                $status = 'Success';
                $redirect_url = '/order_placed';
            } else {
                $status = 'Fail';
                $redirect_url = '/order_fail';
            }
            $request->session()->put('ORDER_STATUS', $status);
            DB::table('orders')
                ->where(['txn_id' => $request->get('payment_request_id')])
                ->update(['payment_status' => $status, 'payment_id' => $request->get('payment_id')]);
            return redirect($redirect_url);
        } else {
            die('Something went wrong');
        }
    }

    // =========================================================
    // ORDERS
    // =========================================================

    public function order(Request $request)
    {
        try {

            // =====================================================
            // LOGGED-IN CUSTOMER
            // =====================================================

            $user = auth('customer_api')->user();


            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            // =====================================================
            // GET ORDERS
            // =====================================================

            $orders = DB::table('orders')
                ->select(
                    'orders.*',
                )
                ->where(
                    'orders.customers_id',
                    $user->id
                )
                ->orderBy(
                    'orders.id',
                    'desc'
                )
                ->get();

            // =====================================================
            // SUCCESS
            // =====================================================

            return response()->json([
                'status' => true,
                'message' => 'Orders fetched successfully.',
                'orders' => $orders,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching orders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // =========================================================
    // ORDER DETAIL
    // =========================================================

    public function order_detail(Request $request, $id)
    {
        try {

            // =====================================================
            // LOGGED-IN CUSTOMER
            // =====================================================

            $user = auth('customer_api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }


            // =====================================================
            // GET ORDER DETAIL
            // =====================================================

            $order_details = DB::table('order_details')

                ->select(
                    'orders.*',
                    'order_details.price',
                    'order_details.quantity',
                    'products.name as pname',
                    'product_attributes.attribute_image',

                    // IMPORTANT:
                    // sizes.size ki jagah actual column use karo
                    'sizes.name as size',

                    'colors.name as color',

                )

                ->leftJoin(
                    'orders',
                    'orders.id',
                    '=',
                    'order_details.order_id'
                )

                ->leftJoin(
                    'product_attributes',
                    'product_attributes.id',
                    '=',
                    'order_details.product_attribute_id'
                )

                ->leftJoin(
                    'products',
                    'products.id',
                    '=',
                    'product_attributes.product_id'
                )

                ->leftJoin(
                    'sizes',
                    'sizes.id',
                    '=',
                    'product_attributes.size_id'
                )

                ->leftJoin(
                    'colors',
                    'colors.id',
                    '=',
                    'product_attributes.color_id'
                )

                // =====================================================
                // ORDER ID
                // =====================================================

                ->where(
                    'orders.id',
                    $id
                )

                // =====================================================
                // ONLY LOGGED-IN CUSTOMER'S ORDER
                // =====================================================

                ->where(
                    'orders.customers_id',
                    $user->id
                )

                ->get();


            // =====================================================
            // ORDER NOT FOUND
            // =====================================================

            if ($order_details->isEmpty()) {

                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.',
                ], 404);
            }


            // =====================================================
            // SUCCESS
            // =====================================================

            return response()->json([
                'status' => true,
                'message' => 'Order detail fetched successfully.',
                'order_details' => $order_details,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching order detail.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function product_review_process(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid = $request->session()->get('FRONT_user_id');

            $arr = [
                "rating" => $request->rating,
                "review" => $request->review,
                "product_id" => $request->product_id,
                "status" => 1,
                "customer_id" => $uid,
                "added_on" => date('Y-m-d h:i:s')
            ];
            $query = DB::table('product_reviews')->insert($arr);
            $status = "success";
            $message = "Thank you for providing your review";
        } else {
            $status = "error";
            $message = "Please login to submit your review";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
