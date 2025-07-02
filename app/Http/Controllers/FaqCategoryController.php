<?php

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqCategoryController extends Controller
{
    
    public function index()
    {
        $categories = FaqCategory::all();
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
    ]);

    $category = FaqCategory::create(['name' => $request->name]);

    // If AJAX request, return JSON
    if ($request->expectsJson()) {
        return response()->json($category);
    }

    return redirect()->route('faq-categories.index')->with('success', 'Category created!');
}

public function destroy(FaqCategory $faqCategory)
{
    // Optional: Check if category has FAQs and handle accordingly
    if ($faqCategory->faqs()->count() > 0) {
        return redirect()->back()->with('error', 'Cannot delete category with FAQs assigned.');
    }

    $faqCategory->delete();

    return redirect()->route('admin.faq-categories.index')->with('success', 'Category deleted successfully.');
}




}

