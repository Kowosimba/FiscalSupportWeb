<?php
// app/Http/Controllers/Admin/ServiceController.php

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
    // Validate the request data
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:services,slug',
        'description' => 'required|string',
        'content' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'is_featured' => 'nullable|boolean',
        'sort_order' => 'nullable|integer',
        'process_steps' => 'nullable|array',
        'process_steps.*.title' => 'required_with:process_steps|string|max:255',
        'process_steps.*.description' => 'required_with:process_steps|string',
    ]);

    try {
        // Prepare the data
        $data = $request->except('_token', 'image', 'process_steps', 'remove_image');
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        
        // Handle process steps
        if ($request->has('process_steps')) {
            $data['process_steps'] = json_encode(array_values($request->process_steps)); // Reindex array
        } else {
            $data['process_steps'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $data['image'] = null;
        }

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        // Create the service
        $service = Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully');

    } catch (\Exception $e) {
        // Log the error for debugging
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
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'process_steps' => 'nullable|array',
            'process_steps.*.title' => 'required_with:process_steps|string|max:255',
            'process_steps.*.description' => 'required_with:process_steps|string',
        ]);

        $data = $request->except('_token', '_method', 'image', 'process_steps');
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        
        if ($request->has('process_steps')) {
            $data['process_steps'] = json_encode($request->process_steps);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

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