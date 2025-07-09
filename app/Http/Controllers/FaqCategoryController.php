<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::withCount('activeFaqs')->orderBy('order')->get();
        return view('admin.faq_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.faq_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faq_categories,name',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $category = FaqCategory::create([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true
        ]);

        // If AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json($category);
        }

        return redirect()->route('admin.faq-categories.index')->with('success', 'Category created successfully!');
    }

    public function destroy(FaqCategory $faqCategory)
    {
        // Check if category has FAQs and handle accordingly
        if ($faqCategory->activeFaqs()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with active FAQs. Please move or delete the FAQs first.');
        }

        $faqCategory->delete();

        return redirect()->route('admin.faq-categories.index')->with('success', 'Category deleted successfully.');
    }
}
