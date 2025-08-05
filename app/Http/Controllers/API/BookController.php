<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\FavoriteBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class BookController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Book::with('category', 'chapters');


        if ($request->has('category_id')) {
            $query->where('book_category_id', $request->category_id);
        }

        if ($request->has('is_audio')) {
            $query->where('is_audio', $request->is_audio);
        }

        if ($request->has('is_reader')) {
            $query->where('is_reader', $request->is_reader);
        }

        if ($request->has('is_popular')) {
            $query->where('is_popular', $request->is_popular);
        }

        if ($request->has('author_name')) {
            $query->where('author_name', 'like', '%' . $request->author_name . '%');
        }

        $books = $query->latest()->paginate(10);
        return $this->paginated($books);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $data = $request->validate([
            'book_category_id' => 'required|exists:book_categories,id',
            'book_name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'author_name' => 'required|string|max:255',
            'is_popular' => 'boolean',
            'is_audio' => 'boolean',
            'is_reader' => 'boolean',
            'made_into_movie' => 'boolean',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'chapters' => 'array',
            'chapters.*.chapter_title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
        ]);

        $book = Book::create($data);

        if (!empty($data['chapters'])) {
            foreach ($data['chapters'] as $chapter) {
                $chapter['book_id'] = $book->id;
                BookChapter::create($chapter);
            }
        }

        return $this->success($book->load('chapters'), 'Book created successfully.');
    }

    public function show($id)
    {
        $book = Book::with('category', 'chapters')->findOrFail($id);
        $this->authorize('view', $book);

        return $this->success($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $this->authorize('update', $book);

        $data = $request->validate([
            'book_category_id' => 'sometimes|exists:book_categories,id',
            'book_name' => 'sometimes|string|max:255',
            'about' => 'nullable|string',
            'author_name' => 'sometimes|string|max:255',
            'is_popular' => 'boolean',
            'is_audio' => 'boolean',
            'is_reader' => 'boolean',
            'made_into_movie' => 'boolean',
            'price' => 'numeric|min:0',
            'quantity' => 'integer|min:0',
            'image_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'discount' => 'nullable|numeric|min:0|max:100',
            'chapters' => 'array',
            'chapters.*.chapter_title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
        ]);

        $book->update($data);

        if (!empty($data['chapters'])) {
            $book->chapters()->delete();
            foreach ($data['chapters'] as $chapter) {
                $chapter['book_id'] = $book->id;
                BookChapter::create($chapter);
            }
        }

        return $this->success($book->load('chapters'), 'Book updated successfully.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $this->authorize('delete', $book);
        $book->delete();

        return $this->success([], 'Book deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $book = Book::findOrFail($id);
        $this->authorize('update', $book);
        $book->is_popular = !$book->is_popular;
        $book->save();

        return $this->success($book, 'Book status toggled.');
    }

    public function popular()
    {
        
        $books = Book::with('category')
            ->where('is_popular', true)
            ->latest()
            ->get();
            

        return $this->success($books, 'Popular books.');
    }

    public function toggleFavorite($id)
    {
        $userId = Auth::id();

        $existing = FavoriteBook::where('user_id', $userId)
            ->where('book_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            return $this->success([], 'Book removed from favorites.');
        }

        FavoriteBook::create([
            'user_id' => $userId,
            'book_id' => $id,
        ]);

        return $this->success([], 'Book added to favorites.');
    }

    public function favoriteBooks()
    {
        $userId = Auth::id();

        $books = Book::with('category')
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('book_id')
                    ->from('favorite_books')
                    ->where('user_id', $userId);
            })->get();

        return $this->success($books, 'Favorite books fetched.');
    }
}
