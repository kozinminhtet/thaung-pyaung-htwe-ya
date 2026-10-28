<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->withCount('posts')
            ->orderBy('type')->orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        Category::create($this->validatedData($request));

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'This category is assigned to posts. Reassign those posts before deleting it.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'icon' => ['nullable', 'string', 'max:50'],
            'type' => ['required', Rule::in(['video', 'article'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);
    }
}
