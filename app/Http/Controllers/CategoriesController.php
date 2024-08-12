<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $categories = Category::with(['parent'])
            ->filter(['search' => $query])
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $category = new Category();
        $parents = Category::all();
        return view('dashboard.categories.create', compact('category', 'parents'));
    }



    public function edit(string $id)
    {
        try {
            $category = Category::with('products')->findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }

        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id);
            })
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    public function show(Category $category)
    {
        $products = $category->products()->where('status', 'active')->get();

        return view('dashboard.categories.show', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function showProducts(Category $category)
    {
        $products = $category->products()->where('status', 'active')->paginate(10);

        return view('dashboard.categories.products', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->except('image');
        $data['slug'] = Str::slug($request->post('name')); // Ensure slug is created from name
        $data['image'] = $this->uploadImage($request);

        $category = Category::create($data);

        if ($request->has('products')) {
            $category->products()->attach($request->input('products'));
        }

        return redirect()->route('categories.index')->with('success', 'Category created');
    }

    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }

        $old_image = $category->image;
        $data = $request->except('image');
        $data['slug'] = Str::slug($request->post('name')); // Ensure slug is created from name
        $new_image = $this->uploadImage($request);

        if ($new_image) {
            $data['image'] = $new_image;
        }

        $category->update($data);

        if ($request->has('products')) {
            $category->products()->sync($request->input('products'));
        }

        if ($old_image && $new_image) {
            try {
                Storage::disk('public')->delete($old_image);
            } catch (Exception $e) {
                return redirect()->route('categories.index')->with('error', 'Failed to delete old image');
            }
        }

        return redirect()->route('categories.index')->with('success', 'Category updated');
    }


    public function destroy(string $id)
{
    try {
        $category = Category::findOrFail($id);
        Log::info('Category found: ' . $category->id);
        $category->products()->detach();
        $category->delete();
        Log::info('Category deleted: ' . $category->id);
    } catch (Exception $e) {
        Log::error('Category deletion error: ' . $e->getMessage());
        return redirect()->route('categories.index')->with('error', 'Category not found');
    }
    return redirect()->route('categories.index')->with('success', 'Category deleted');
}



    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $path = $file->store('uploads', 'public');
        return $path;
    }

    public function trash()
{
    $categories = Category::onlyTrashed()->paginate(10);
    return view('categories.trashed', compact('categories'));
}

// public function restore(Request $request, $id)
// {
//     try {
//         $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
//         $category->restore();
//     } catch (Exception $e) {
//         return redirect()->route('categories.trash')->with('error', 'Category not found');
//     }
//     return redirect()->route('categories.trash')->with('success', 'Category restored');
// }

// public function forceDelete($id)
// {
//     try {
//         $category = Category::withTrashed()->where('id', $id)->firstOrFail();
//         $category->products()->detach();
//         $category->forceDelete();

//         if ($category->image) {
//             Storage::disk('public')->delete($category->image);
//         }
//     } catch (Exception $e) {
//         return redirect()->route('categories.trash')->with('error', 'Category not found');
//     }
//     return redirect()->route('categories.trash')->with('success', 'Category permanently deleted');
// }



}
