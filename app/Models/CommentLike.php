<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'comment_id', 'impression_type'];

    public function addLikesAndDislikesToComments(Request $request)
    {
        try {
            $user = auth()->user();
            $impressionType = $request->impression_type;
            $comment = $this->firstOrNew([
                'user_id' => $user->id,
                'comment_id' => $request->comment_id,
            ]);

            if ($comment->exists && $impressionType == $comment->impression_type) {
                $comment->delete();
                return response()->json(['status' => 200, 'message' => 'Like or dislike has been removed'], 200);
            }

            $comment->impression_type = $impressionType;
            $comment->save();

            return response()->json(['status' => 200, 'message' => 'Request passed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
