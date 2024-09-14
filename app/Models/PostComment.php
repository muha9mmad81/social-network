<?php

namespace App\Models;

use App\Http\Resources\PostCommentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'post_id', 'user_id', 'comment', 'parent'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id')->where('impression_type', 1);
    }

    public function dislikes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id')->where('impression_type', 2);
    }

    public function addPostComment(Request $request)
    {
        try {
            $user = auth()->user();
            $this->post_id = $request->post_id;
            $this->user_id = $user->id;
            $this->comment = $request->comment;
            $this->parent = $request->comment_id ?? 0;
            $this->save();

            return response()->json(['status' => 200, 'message' => 'Comment has been added to the post.', 'data' => new PostCommentResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function editPostComment(Request $request, $commentId)
    {
        try {
            $comment = $this->find($commentId);
            $comment->comment = $request->comment;
            $comment->update();

            return response()->json(['status' => 200, 'message' => 'Comment has been updated', 'data' => new PostCommentResource($comment)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function deletePostComment(Request $request, $commentId)
    {
        try {
            $comment = $this->find($commentId);
            $comment->delete();
            $this->where('parent', $commentId)->delete();
            CommentLike::where('comment_id', $commentId)->delete();

            return response()->json(['status' => 200, 'message' => 'Comment has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
