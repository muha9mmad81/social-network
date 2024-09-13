<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'post_id', 'impression_type'];

    public function addLikesAndDislikesToPosts(Request $request)
    {
        try {
            $user = auth()->user();
            $impressionType = $request->impression_type;
            $post = $this->firstOrNew([
                'user_id' => $user->id,
                'post_id' => $request->post_id,
            ]);

            if ($post->exists && $impressionType == $post->impression_type) {
                $post->delete();
                return response()->json(['status' => 200, 'message' => 'Like or dislike has been removed'], 200);
            }

            $post->impression_type = $impressionType;
            $post->save();

            return response()->json(['status' => 200, 'message' => 'Request passed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
