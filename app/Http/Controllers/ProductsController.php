<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
{
    $query = $request->input('search');
    $stores = Store::all();
    $categories = Category::all();

    // Eager load category and store relationships
    $products = Product::with(['category', 'store'])
        ->filter($request->query())
        ->when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('name', 'like', "%{$query}%");
        })
        ->orderBy('name')
        ->paginate(10);

    return view('dashboard.products.index', compact('products', 'stores', 'categories'));
}


public function create()
{
    $categories = Category::all();
    $stores = Store::all();

    return view('dashboard.products.create', [
        'categories' => $categories,
        'stores' => $stores,
        'product' => new Product(), // Or the product you're editing
    ]);
}



public function store(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'regular_price' => 'required|numeric',
        'store_id' => 'required|exists:stores,id',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'required|in:active,inactive',
    ]);

    // Generate unique slug from the product name
    $slug = Str::slug($validatedData['name'], '-');
    $slugCount = Product::where('slug', 'like', $slug . '%')->count();
    if ($slugCount > 0) {
        $slug .= '-' . ($slugCount + 1);
    }
    $validatedData['slug'] = $slug;

    // Generate SKU automatically
    $validatedData['SKU'] = 'SKU-' . strtoupper(Str::random(8));

    // Handle image upload
    if ($request->hasFile('image')) {
        $validatedData['image'] = $request->file('image')->store('products', 'public');
    } else {
        $validatedData['image'] = null;
    }

    // Handle the images field
    $validatedData['images'] = json_encode([]); // Default to an empty array

    // Create the product
    $product = Product::create($validatedData);

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}



    public function edit(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $stores = Store::all();
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'stores', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'store_id' => 'required|exists:stores,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product->update($validatedData);
        $product->category()->sync($request->input('categories'));

        $tags = explode(',', $request->post('tags'));
        $tag_ids = [];
        foreach ($tags as $t_name) {
            $slug = Str::slug($t_name);
            $tag = Tag::where('slug', $slug)->first();
            if (!$tag) {
                $tag = Tag::create([
                    'name' => $t_name,
                    'slug' => $slug,
                ]);
            }
            $tag_ids[] = $tag->id;
        }

        $product->tags()->sync($tag_ids);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {

    }
}
