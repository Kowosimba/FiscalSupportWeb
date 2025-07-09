<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Public FAQ page
    public function index()
{
    $categories = FaqCategory::where('is_active', true)
        ->orderBy('order')
        ->with(['activeFaqs'])
        ->get();


        return view('Home.faqs', compact('categories'));
    }

    // Admin FAQ index
    public function adminIndex()
    {
        $faqs = Faq::with('category')->orderBy('order')->get();
        $categories = FaqCategory::orderBy('name')->get();
        return view('admin.Contents.Faqspage', compact('faqs', 'categories'));
    }

    // Store new FAQ

    public function create()
{
    $categories = FaqCategory::all(); // Fetch categories for dropdown
    return view('admin.contents.faqsform', compact('categories'));
}
public function store(Request $request)
{
    $request->validate([
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'faq_category_id' => 'nullable|exists:faq_categories,id',
        'new_category_name' => 'nullable|string|max:255',
        'order' => 'nullable|integer',
        'is_active' => 'required|boolean',
    ]);

    // Determine which category to use
    $categoryId = $request->faq_category_id;
    
    // If new category name is provided, create it
    if (!$categoryId && $request->new_category_name) {
        $category = FaqCategory::create([
            'name' => $request->new_category_name,
            'order' => FaqCategory::max('order') + 1,
            'is_active' => true
        ]);
        $categoryId = $category->id;
    }

    // Validate that we have a category
    if (!$categoryId) {
        return back()->withErrors(['category' => 'Please select an existing category or create a new one.']);
    }

    Faq::create([
        'question' => $request->question,
        'answer' => $request->answer,
        'category_id' => $categoryId,
        'order' => $request->order ?? 0,
        'is_active' => $request->is_active,
    ]);

    return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully!');
}




// Similar adjustments for update method
public function update(Request $request, Faq $faq)
{
    $validated = $request->validate([
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'category_id' => 'required|exists:faq_categories,id',
        'order' => 'nullable|integer',
        'is_active' => 'required|boolean',
    ]);

    $faq->update($validated);

    if ($request->ajax()) {
        return response()->json(['success' => true]);
    }

    return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully');
}

    // Delete FAQ
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully');
    }

public function edit(Faq $faq)
{
    $categories = FaqCategory::all();
    return view('admin.Contents.faqsedit', compact('faq', 'categories'));
}

}