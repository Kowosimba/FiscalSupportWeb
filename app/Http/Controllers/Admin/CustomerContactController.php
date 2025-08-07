<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerContact;
use Illuminate\Http\Request;

class CustomerContactController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerContact::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.contacts.index', compact('contacts', 'search'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:50',
            'company'   => 'nullable|string|max:255',
            'position'  => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Fix: Proper boolean handling
        $data['is_active'] = $request->has('is_active') ? true : false;

        CustomerContact::create($data);

        // Return to the same form with success message for toastr
        return redirect()->back()
            ->with('success', 'Contact created successfully! You can add another contact or go back to the list.');
    }

    // ADD THIS MISSING METHOD
    public function show(CustomerContact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(CustomerContact $contact)
    {
        return view('admin.contacts.create', compact('contact'));
    }

    public function update(Request $request, CustomerContact $contact)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:50',
            'company'   => 'nullable|string|max:255',
            'position'  => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Fix: Proper boolean handling
        $data['is_active'] = $request->has('is_active') ? true : false;

        $contact->update($data);

        // Return to the same form with success message for toastr
        return redirect()->back()
            ->with('success', 'Contact updated successfully!');
    }

    public function destroy(CustomerContact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}