<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('dashboard.banners.index', compact('banners'));
    }

    public function create()
    {
        $banner = new Banner();
        return view('dashboard.banners.create', compact('banner'));
    }

    public function store(Request $request)
    {
        $request->validate(Banner::rules());

        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);

        Banner::create($data);

        return redirect()->route('dashboard.banners.index')->with('success', 'Banner created successfully');
    }

    public function show(Banner $banner)
    {
        return view('dashboard.banners.show', compact('banner'));
    }

    public function edit(string $id)
    {
        try {
            $banner = Banner::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.banners.index')->with('error', 'Banner not found');
        }

        return view('dashboard.banners.edit', compact('banner'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(Banner::rules());

        $banner = Banner::find($id);

        if (!$banner) {
            return redirect()->route('dashboard.banners.index')->with('error', 'Banner not found');
        }

        $old_image = $banner->image;
        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $new_image = $this->uploadImage($request);
            if ($new_image) {
                $data['image'] = $new_image;
            }

            // Delete the old image
            if ($old_image && $new_image) {
                Storage::disk('public')->delete($old_image);
            }
        }

        $banner->update($data);

        return redirect()->route('dashboard.banners.index')->with('success', 'Banner updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            $banner = Banner::findOrFail($id);

            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $banner->delete();

            return redirect()->route('dashboard.banners.index')->with('success', 'Banner deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('dashboard.banners.index')->with('error', 'Failed to delete banner');
        }
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('banners', 'public');
    }
}

