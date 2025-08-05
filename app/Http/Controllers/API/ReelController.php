<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class ReelController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'media_url' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $reel = Reel::create([
            'user_id' => auth()->id(),
            'media_url' => $request->media_url,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return $this->success($reel, 'Reel created successfully.');
    }

    public function index()
    {
        $reels = Reel::with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate(10);

        return $this->paginated($reels, 'Reels fetched successfully.');
    }

    public function destroy($id)
    {
        $reel = Reel::find($id);

        if (!$reel) {
            return $this->error('Reel not found.', 404);
        }

        $this->authorize('delete', $reel);
        $reel->delete();

        return $this->success([], 'Reel deleted successfully.');
    }

}
