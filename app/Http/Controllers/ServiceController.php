<?php
// app/Http/Controllers/ServiceController.php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller {
    public function index(Request $request)
    {
        $services = Service::orderBy('sort_order')->get();
        
        // Check if we're editing a service
        $editService = null;
        if ($request->has('edit')) {
            $editService = Service::findOrFail($request->edit);
        }
        
        return view('Home.services', [
            'services' => $services,
            'editService' => $editService
        ]);
    }


    public function show($slug)
{
    $service = Service::with('resources')
        ->where('slug', $slug)
        ->firstOrFail();
        
    $relatedServices = Service::where('id', '!=', $service->id)
        ->inRandomOrder()
        ->limit(5)
        ->get();
    
    return view('Home.servicesdetails', compact('service', 'relatedServices'));
}
}