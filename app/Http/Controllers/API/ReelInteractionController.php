<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReelLike;
use App\Models\ReelComment;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ReelInteractionController extends Controller
{
    use ApiResponse;

    public function toggleLike($id)
    {
        $user = auth()->user();

        $like = ReelLike::where('user_id', $user->id)->where('reel_id', $id)->first();

        if ($like) {
            $like->delete();
            return $this->success(null, 'Reel unliked.');
        }

        ReelLike::create([
            'user_id' => $user->id,
            'reel_id' => $id,
        ]);

        return $this->success([], 'Reel liked.');
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment = ReelComment::create([
            'user_id' => auth()->id(),
            'reel_id' => $id,
            'comment' => $request->comment,
        ]);

        return $this->success($comment, 'Comment posted.');
    }

    public function listComments($id)
    {
        $comments = ReelComment::where('reel_id', $id)
            ->with('user:id,name,profile_image')
            ->latest()
            ->paginate(10);

        return $this->paginated($comments, 'Comments fetched.');
    }

    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment = ReelComment::findOrFail($id);
        $this->authorize('update', $comment); 

        $comment->update(['comment' => $request->comment]);

        return $this->success($comment, 'Comment updated.');
    }

    public function deleteComment($id)
    {
        $comment = ReelComment::findOrFail($id);
        $this->authorize('delete', $comment); 

        $comment->delete();

        return $this->success([], 'Comment deleted.');
    }

}
