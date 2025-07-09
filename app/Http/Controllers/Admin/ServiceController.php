<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('sort_order')->get();
        $editService = null;

        if ($request->has('edit')) {
            $editService = Service::findOrFail($request->edit);
        }

        return view('admin.services.index', compact('services', 'editService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        try {
            $data = $request->except('_token', 'image', 'remove_image');
            $data['slug'] = $request->slug ?: Str::slug($request->title);
            $data['is_featured'] = $request->has('is_featured');


            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('services', 'public');
            } elseif ($request->has('remove_image') && $request->remove_image) {
                $data['image'] = null;
            }

            // Handle is_featured checkbox
            $data['is_featured'] = $request->has('is_featured');

            Service::create($data);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service created successfully');

        } catch (\Exception $e) {
            Log::error('Service creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating service: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,'.$service->id,
            'description' => 'required|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token', '_method', 'image', 'remove_image');
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');


        // Handle image upload
        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        } elseif ($request->has('remove_image') && $request->remove_image) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = null;
        }

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        // Delete all associated resources first
        foreach ($service->resources as $resource) {
            Storage::disk('public')->delete($resource->file_path);
            $resource->delete();
        }

        // Delete service image
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully');
    }
}
