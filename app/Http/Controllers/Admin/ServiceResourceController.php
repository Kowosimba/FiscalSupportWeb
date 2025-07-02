<?php
// app/Http/Controllers/Admin/ServiceResourceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ServiceResourceController extends Controller
{
    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('service_resources', 'public');

            $resource = $service->resources()->create([
                'title' => $validated['title'],
                'file_path' => $path,
                'file_size' => $this->formatBytes($file->getSize()),
                'file_type' => $file->getClientOriginalExtension(),
            ]);

            return back()->with('success', 'Resource added successfully');
        } catch (\Exception $e) {
            Log::error('Resource creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to add resource: '.$e->getMessage());
        }
    }

    public function destroy(Service $service, ServiceResource $resource)
    {
        try {
            Storage::disk('public')->delete($resource->file_path);
            $resource->delete();

            return back()->with('success', 'Resource deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete resource:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete resource. Please try again.');
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function download(ServiceResource $resource)
{
    $filePath = $resource->file_path; // e.g., service_resources/file.pdf
    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File not found.');
    }
    $absolutePath = Storage::disk('public')->path($filePath);
    return response()->download($absolutePath, $resource->title . '.' . $resource->file_type);
}

}