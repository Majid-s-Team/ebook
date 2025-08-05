<?php

namespace App\Http\Controllers\Api;

use App\Models\BookCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\FavoriteCategory;
use Illuminate\Support\Facades\Auth;

class BookCategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $this->authorize('viewAny', BookCategory::class);
        $categories = BookCategory::latest()->paginate(10);
        return $this->paginated($categories);
    }

    public function store(Request $request)
    {
        $this->authorize('create', BookCategory::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_popular' => 'boolean',
        ]);

        $category = BookCategory::create($data);
        return $this->success($category, 'Book category created.');
    }

    public function show($id)
    {
        $category = BookCategory::findOrFail($id);
        $this->authorize('view', $category);

        return $this->success($category);
    }

    public function update(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);
        $this->authorize('update', $category);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_popular' => 'boolean',
        ]);

        $category->update($data);
        return $this->success($category, 'Book category updated.');
    }

    public function destroy($id)
    {
        $category = BookCategory::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();

        return $this->success([], 'Book category deleted.');
    }

    public function toggleStatus($id)
    {
        $category = BookCategory::findOrFail($id);
        $this->authorize('update', $category);

        $category->is_active = !$category->is_active;
        $category->save();

        return $this->success($category, 'Status updated.');
    }

    public function popular()
    {
        $categories = BookCategory::where('is_popular', true)->where('is_active', true)->get();
        return $this->success($categories, 'Popular categories.');
    }

    public function toggleFavorite($id)
    {
        $userId = Auth::id();

        $favorite = FavoriteCategory::where('user_id', $userId)
            ->where('book_category_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Category removed from favorites.']);
        }

        FavoriteCategory::create([
            'user_id' => $userId,
            'book_category_id' => $id,
        ]);

        return response()->json(['message' => 'Category added to favorites.']);
    }

    public function favoriteCategories()
    {
        $userId = Auth::id();

        $favorites = BookCategory::whereIn('id', function ($query) use ($userId) {
            $query->select('book_category_id')
                ->from('favorite_categories')
                ->where('user_id', $userId);
        })->get();


        return response()->json(['favorites' => $favorites]);
    }
}
