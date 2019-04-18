<?php

namespace App\Http\Controllers;

use App\Category;
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
            'image' => 'mimes:jpeg,bmp,png'
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

        if ($request->has('file')) {
            $path = $request->file('image')->store(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        return redirect()->route('productList');
    }

    /**
     * Show form for product update
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateForm(Product $product)
    {
        $categories = Category::all();
        return view('products.update', compact('product', 'categories'));
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
            'image' => 'mimes:jpeg,bmp,png'
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
            Storage::driver('s3')->delete($image->filepath);
            $path = $request->file('image')->store(
                'images', 's3'
            );
            $new_image = Image::create(['filepath' => $path]);
            $product->setAttribute('image_id', $new_image->id);
        }

        $product->save();

        return redirect()->route('productList');
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
            Storage::disk('s3')->delete($product->image->filepath);
            $product->delete();
        } catch (\Exception $exception) {
        }
        return redirect()->route('productList');
    }
}
