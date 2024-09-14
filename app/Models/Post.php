<?php

namespace App\Models;

use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'title', 'description', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function videos()
    {
        return $this->hasMany(PostVideo::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->where('parent', 0);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class)->where('impression_type', 1);
    }

    public function dislikes()
    {
        return $this->hasMany(PostLike::class)->where('impression_type', 2);
    }

    public function addPost(Request $request)
    {
        try {
            $user = auth()->user();
            $this->title = $request->title ?? $user->name . ' posted an update';
            $this->description = $request->description ?? null;
            $this->user_id = $user->id;
            $this->type = $request->type ?? 'public';
            $this->save();
            $this->addPostImages($request, $this);
            $this->addPostVideos($request, $this);
            return response()->json(['status' => 200, 'message' => 'Post has been created', 'data' => new PostResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function addPostImages($request, $post)
    {
        if ($request->image) {
            foreach ($request->image as $key => $image) {
                $fileName = $image->getClientOriginalName();
                $file = saveFile($image, 'images/posts', $fileName);
                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $file['name']
                ]);
            }
        }
    }

    public function addPostVideos($request, $post)
    {
        if ($request->video) {
            foreach ($request->video as $key => $video) {
                $fileName = $video->getClientOriginalName();
                $file = saveFile($video, 'videos/posts', $fileName);
                PostVideo::create([
                    'post_id' => $post->id,
                    'video' => $file['name']
                ]);
            }
        }
    }

    public function deletePost(Request $request, $postId)
    {
        try {
            $post = $this->find($postId);
            $post->delete();
            PostImage::where('post_id', $postId)->delete();
            PostVideo::where('post_id', $postId)->delete();
            PostComment::where('post_id', $postId)->delete();
            PostLike::where('post_id', $postId)->delete();

            return response()->json(['status' => 200, 'message' => 'Post has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllMyPosts(Request $request)
    {
        try {
            $user = auth()->user();
            $posts = $this->where('user_id', $user->id)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllPosts(Request $request)
    {
        try {
            $posts = $this->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getPostById(Request $request, $postId)
    {
        try {
            $post = $this->find($postId);
            $data = new PostResource($post);

            return response()->json(['status' => 200, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
