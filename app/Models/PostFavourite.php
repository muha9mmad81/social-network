<?php

namespace App\Models;

use App\Http\Resources\PostFavouriteResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostFavourite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createOrRemovePostFavourite(Request $request)
    {
        try {
            $postId = $request->post_id;
            $userId = $request->user_id;
            $post = $this->where('post_id', $postId)->where('user_id', $userId)->first();

            if ($post) {
                $post->delete();
                return response()->json(['status' => 200, 'message' => 'Post removed from favourites'], 200);
            } else {
                $this->post_id = $postId;
                $this->user_id = $userId;
                $this->save();

                return response()->json(['status' => 200, 'message' => 'Post added to favourites', 'data' => new PostFavouriteResource($this)], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function getMyFavouritePosts(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $favourites = $this->where('user_id', $userId)->get();
            $collection = PostFavouriteResource::collection($favourites);

            return response()->json(['status' => 200, 'data' => $collection], 200);

            return response()->json(['status' => 200, 'message' => 'Post added to favourites'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function getUserFavouritePosts(Request $request, $userId)
    {
        try {
            $favourites = $this->where('user_id', $userId)->get();
            $collection = PostFavouriteResource::collection($favourites);

            return response()->json(['status' => 200, 'data' => $collection], 200);

            return response()->json(['status' => 200, 'message' => 'Post added to favourites'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 500], 500);
        }
    }
}
