<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\ProductInterface;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductRepository implements ProductInterface
{

    public function getOptions($request)
    {
        $query = Product::query();
        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $products = $query->get();
        $product_options = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                $product_options[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'products' => $product_options,
        ]);
    }

    public function getProducts($request)
    {
        $query = Product::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('products.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('products.name', 'like', '%' . $search . '%')
                ->orWhere('products.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('products.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('products.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('products.status', $search);
                }

                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('products.created_at', $date);
                } catch (\Exception $e) {
                    // Invalid date format, ignore
                }
            });
        }

        // Sorting logic
        $sort_column = $request->get('sort_by', null);
        $sort_order = $request->get('order', 'asc');

        if (!empty($sort_column)) {
            // Prefix with table if needed
            if (!str_contains($sort_column, '.')) {
                $sort_column = 'products.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('products.name', 'asc');
        }

        // Pagination setup
        $records_per_page = (int) $request->get('records_per_page', 10);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_records = $query->count();
        $total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 0;

        $query->offset($offset)->limit($records_per_page);

        $query->select([
            'products.*'
        ]);

        $products = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'products' => $products,
            'total_records' => $total_records,
            'records_per_page' => $records_per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'page' => $page,
        ]);
    }


    public function create($request)
    {
        try {
           $user = $request->user();
            $data = $request->except([
                'productImages',
                'productAttribute'
            ]);

            // dd($data);

        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        // Auto Slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Product Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $data['image'] = 'products/' . $imageName;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */

        $product = Product::create($data);

        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        */

        if ($request->has('productImages')) {
            foreach ($request->productImages as $imageRow) {
                $imagePath = null;
                if (isset($imageRow['image']) && $imageRow['image']) {
                    $file = $imageRow['image'];
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('productImages'), $fileName);
                    $imagePath = 'productImages/' . $fileName;
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Product Attributes
        |--------------------------------------------------------------------------
        */
        if ($request->has('productAttribute')) {
            foreach ($request->productAttribute as $attribute) {
                $attributeImage = null;
                if (isset($attribute['image']) && $attribute['image']) {
                    $file = $attribute['image'];
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('productAttributes'), $fileName);
                    $attributeImage = 'productAttributes/' . $fileName;
                }

               $product_attribute =  ProductAttribute::create([
                    'product_id' => $product->id,
                    'color_id' => $attribute['color_id'],
                    'size_id' => $attribute['size_id'],
                    'sku' => $attribute['sku'],
                    'price' => $attribute['price'],
                    'maximum_retail_price' => $attribute['sale_price'],
                    'quantity' => $attribute['quantity'],
                    'attribute_image' => $attributeImage,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                // dd($product_attribute);
            }
        }
            return response()->json([
                'product' => $product,
                'status' => 'success',
                'message' => 'product created successfully'
            ], 201);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update($request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->except([
            'productImages',
            'productAttribute',
            '_method'
        ]);

        $data['updated_by'] = $request->user()->id;

        // Main Product Image
        if ($request->hasFile('image')) {

            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);

            $data['image'] = 'products/'.$imageName;
        }

        $product->update($data);

        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        */

        if ($request->has('productImages')) {

            // Request wali IDs
            $requestImageIds = collect($request->productImages)
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete removed images
            $deletedImages = ProductImage::where('product_id', $product->id)
                ->whereNotIn('id', $requestImageIds)
                ->get();

            foreach ($deletedImages as $img) {

                if ($img->image && file_exists(public_path($img->image))) {
                    unlink(public_path($img->image));
                }
                $img->delete();
            }

            // Update old + create new
            foreach ($request->productImages as $item) {

                if (!empty($item['id'])) {
                    $productImage = ProductImage::find($item['id']);
                } else {
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->created_by = $request->user()->id;
                }
                $productImage->updated_by = $request->user()->id;

                if (
                    isset($item['image']) &&
                    $item['image'] instanceof \Illuminate\Http\UploadedFile
                ) {

                    if (
                        $productImage->image &&
                        file_exists(public_path($productImage->image))
                    ) {
                        unlink(public_path($productImage->image));
                    }

                    $file = $item['image'];

                    $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

                    $file->move(public_path('productImages'), $name);

                    $productImage->image = 'productImages/'.$name;
                }
                $productImage->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Product Attributes
        |--------------------------------------------------------------------------
        */

       if ($request->has('productAttribute')) {

        $requestAttributeIds = collect($request->productAttribute)
            ->pluck('id')
            ->filter()
            ->toArray();

        $deletedAttributes = ProductAttribute::where('product_id', $product->id)
            ->whereNotIn('id', $requestAttributeIds)
            ->get();

        foreach ($deletedAttributes as $attribute) {

            if (
                $attribute->attribute_image &&
                file_exists(public_path($attribute->attribute_image))
            ) {
                unlink(public_path($attribute->attribute_image));
            }

            $attribute->delete();
        }

        foreach ($request->productAttribute as $item) {

            if (!empty($item['id'])) {

                $attribute = ProductAttribute::find($item['id']);

            } else {

                $attribute = new ProductAttribute();
                $attribute->product_id = $product->id;
                $attribute->created_by = $request->user()->id;
            }

            $attribute->color_id = $item['color_id'];
            $attribute->size_id = $item['size_id'];
            $attribute->sku = $item['sku'];
            $attribute->price = $item['price'];
            $attribute->maximum_retail_price = $item['sale_price'];
            $attribute->quantity = $item['quantity'];
            $attribute->updated_by = $request->user()->id;

            if (
                isset($item['image']) &&
                $item['image'] instanceof \Illuminate\Http\UploadedFile
            ) {

                if (
                    $attribute->attribute_image &&
                    file_exists(public_path($attribute->attribute_image))
                ) {
                    unlink(public_path($attribute->attribute_image));
                }

                $file = $item['image'];

                $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

                $file->move(public_path('productAttributes'), $name);

                $attribute->attribute_image = 'productAttributes/'.$name;
            }

            $attribute->save();
        }
    }

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully.'
        ]);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $product = Product::findOrFail($id);

        if (!$product) {
            throw new \Exception('product not found.');
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'product deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $product = Product::with([
            'createdBy',
            'updatedBy',
            'productImages',
            'productAttributes',
            'productAttributes.size',
            'productAttributes.color',
            'category',
            'brand',
            ])->findOrFail($id);


            // dd($product);


        if (!$product) {
            throw new \Exception('product not found.');
        }

        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }
}