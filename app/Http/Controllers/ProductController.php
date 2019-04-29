<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryFieldValue;
use App\Image;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * List products
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::query()
            ->with('image', 'category', 'creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * List products
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/products",
     *     operationId="listProducts",
     *     tags={"Products"},
     *     summary="List products",
     *     description="Returns an array of products",
     *     @OA\Parameter(
     *         name="page",
     *         description="Current page number in the pagination (starts at 1)",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully got products",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Product"
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="current_page",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="last_page",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="total",
     *                 type="integer"
     *             )
     *         )
     *     )
     * )
     */
    public function indexApi()
    {
        $products = Product::query()
            ->with('image', 'category', 'creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return Response(
            $products,
            200
        );
    }

    /**
     * Show add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        if (auth()->user()->can('manage product')) {
            $categories = Category::all();
        } else {
            $categories = User::query()->find(auth()->user()->id)->categories()->get();
        }
        return view('products.new', compact('categories'));
    }

    /**
     * Create a new product
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return view('admin.products.new', $errors);
        }

        $product = new Product;
        $product->fill(
            $request->only($product->getFillable())
        );
        $product->setAttribute('user_id', auth()->user()->id);

        if ($request->has('image')) {
            $path = $request->file('image')->storePublicly(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        return redirect()->route('productList');
    }

    /**
     * Create new product
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     * @OA\Post(
     *     path="/api/products",
     *     operationId="createProduct",
     *     tags={"Products"},
     *     summary="Create new product",
     *     description="Returns new product",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file"
     *                 ),
     *                 required={"name", "category_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully created a product",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Product"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="category_id",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     security={ { "passport": {} } }
     * )
     */
    public function storeApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            return Response(
                $validator->errors(),
                400
            );
        }

        $product = new Product;
        $product->fill(
            $request->only($product->getFillable())
        );
        $product->setAttribute('user_id', auth()->user()->id);

        if ($request->has('image')) {
            $path = $request->file('image')->storePublicly(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        return Response(
            $product,
            200
        );
    }

    /**
     * Show form for product update
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateForm(Product $product)
    {
        $categories = Category::all();

        $custom_fields = $product->customFields()->get();
        $custom_field_values = $product->customFieldValues()
            ->whereIn('category_field_id', array_map(function ($field) {
                return $field['id'];
            }, $custom_fields->toArray()))
            ->get();

        $custom_field_with_values = [];
        foreach ($custom_fields as $field) {
            global $field_id;
            $field_id = $field->id;
            $found_values = array_filter($custom_field_values->toArray(), function ($field_value) {
                global $field_id;
                return $field_value['category_field_id'] === $field_id;
            });
            $custom_field_with_values[$field['id']] = [
                $field,
                (count($found_values) > 0) ? array_pop($found_values) : null
            ];
        }

        return view('products.update', compact('product', 'categories', 'custom_field_with_values'));
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'image' => 'mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return view('admin.products.update', $errors);
        }

        $product->fill(
            $request->only($product->getFillable())
        );

        if ($request->hasFile('image')) {
            $image = Image::find($product->image_id);
            Storage::disk('s3')->delete($image->filepath);
            $path = $request->file('image')->storePublicly(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        if ($request->has('customFieldValue') && count($request['customFieldValue']) > 0) {
            foreach ($request['customFieldValue'] as $field_id => $value_arr) {
                if ($value_arr['valueId']) {
                    $field_value = CategoryFieldValue::query()->find($value_arr['valueId']);
                } else {
                    $field_value = $product->customFieldValues()->create([
                        'category_field_id' => $field_id
                    ]);
                }
                $field_value->update([
                    'value' => (string)$value_arr['value']
                ]);
            }
        }

        return redirect()->route('productUpdateForm', compact('product'));
    }

    /**
     * Update a product
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     *
     * @OA\Put(
     *     path="/api/products/{id}",
     *     operationId="updateAProduct",
     *     tags={"Products"},
     *     summary="Update a product",
     *     description="Updates the product specified in the path and returns the updated product",
     *     @OA\Parameter(
     *         name="id",
     *         description="Id of the product",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully updated a product",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Product"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="category_id",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     security={ { "passport": {} } }
     * )
     */
    public function updateApi(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'image' => 'mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            return Response(
                $validator->errors(),
                400
            );
        }

        $product->fill(
            $request->only($product->getFillable())
        );

        if ($request->hasFile('image')) {
            $image = Image::find($product->image_id);
            Storage::disk('s3')->delete($image->filepath);
            $path = $request->file('image')->storePublicly(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        return Response(
            $product,
            200
        );
    }

    /**
     * Show product forbidden message
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forbidden()
    {
        return view('products.forbidden');
    }

    /**
     * Confirmation for delete
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteConfirm(Product $product)
    {
        return view('products.delete', compact('product'));
    }

    /**
     * Delete product
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Product $product)
    {
        try {
            $product->load('image');
            if ($product->image) {
                Storage::disk('s3')->delete($product->image->filepath);
            }
            $product->delete();
        } catch (\Exception $exception) {
        }
        return redirect()->route('productList');
    }

    /**
     * Delete a product
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     *
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     operationId="deleteAProduct",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     description="Deletes the product specified in the path",
     *     @OA\Parameter(
     *         name="id",
     *         description="Id of the product",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully deleted a product"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     security={ { "passport": {} } }
     * )
     */
    public function deleteApi(Product $product)
    {
        try {
            $product->load('image');
            if ($product->image) {
                Storage::disk('s3')->delete($product->image->filepath);
            }
            $product->delete();
        } catch (\Exception $exception) {
            return Response(
                ['message' => $exception->getMessage()],
                400
            );
        }
        return Response(
            true,
            200
        );
    }
}
