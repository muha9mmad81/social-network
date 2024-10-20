<?php

namespace App\Models;

use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'title', 'description', 'type', 'group_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
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
            $this->group_id = $request->group_id;
            $this->type = $request->type ?? 'public';
            $this->save();
            $this->addPostImages($request, $this);
            $this->addPostVideos($request, $this);
            return response()->json(['status' => 200, 'message' => 'Post has been created', 'data' => new PostResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function sharePost(Request $request, $postId)
    {
        try {
            $user = auth()->user();
            $originalPost = Post::findOrFail($postId);

            $this->user_id = $user->id;
            $this->title = $originalPost->title;
            $this->description = $originalPost->description;
            $this->type = $originalPost->type;
            $this->shared_post_id = $originalPost->id;
            $this->is_shared = 1;
            $this->save();

            $this->duplicatePostImages($originalPost, $this);
            $this->duplicatePostVideos($originalPost, $this);

            return response()->json(['status' => 200, 'message' => 'Post has been shared', 'data' => new PostResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 401], 401);
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

    public function duplicatePostImages($originalPost, $sharedPost)
    {
        foreach ($originalPost->images as $image) {
            PostImage::create([
                'post_id' => $sharedPost->id,
                'image' => $image->image // Use the same image path
            ]);
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

    public function duplicatePostVideos($originalPost, $sharedPost)
    {
        foreach ($originalPost->videos as $video) {
            PostVideo::create([
                'post_id' => $sharedPost->id,
                'video' => $video->video // Use the same video path
            ]);
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
            $posts = $this->where('user_id', $user->id)->where('group_id', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllPosts(Request $request)
    {
        try {
            $posts = $this->where('group_id', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllMyGroupPosts(Request $request)
    {
        try {
            $user = auth()->user();
            $posts = $this->where('user_id', $user->id)->where('group_id', '!=', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllGroupPosts(Request $request)
    {
        try {
            $posts = $this->where('group_id', '!=', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getPostAccordingToGroup(Request $request)
    {
        try {
            $posts = $this->where('group_id', $request->group_id)->orderByDesc('id')->first();
            $collection = new PostResource($posts);

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

    public function getUserMedia(Request $request, $userId)
    {
        try {
            $user = User::with(['post.images', 'post.videos'])->find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $posts = $user->post()->with(['images', 'videos'])->get();
            $type = $request->query('type', 'both');
            $media = [];

            if ($type === 'images' || $type === 'both') {
                $images = $posts->pluck('images')->flatten()->map(function ($image) {
                    return asset('images/posts/' . $image->image);
                });
                $media['images'] = $images;
            }

            if ($type === 'videos' || $type === 'both') {
                $videos = $posts->pluck('videos')->flatten()->map(function ($video) {
                    return asset('images/posts/' . $video->video);
                });
                $media['videos'] = $videos;
            }
            return response()->json($media);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 500], 500);
        }
    }


    //

    public function getAllUserPosts(Request $request, $userId)
    {
        try {
            $posts = $this->where('user_id', $userId)->where('group_id', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllUserGroupPosts(Request $request, $userId)
    {
        try {
            $posts = $this->where('user_id', $userId)->where('group_id', '!=', null)->orderByDesc('id')->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getUserMediaWithoutAuthentication(Request $request, $userId)
    {
        try {
            $user = User::with(['post.images', 'post.videos'])->find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $posts = $user->post()->with(['images', 'videos'])->get();
            $type = $request->query('type', 'both');
            $media = [];

            if ($type === 'images' || $type === 'both') {
                $images = $posts->pluck('images')->flatten()->map(function ($image) {
                    return asset('images/posts/' . $image->image);
                });
                $media['images'] = $images;
            }

            if ($type === 'videos' || $type === 'both') {
                $videos = $posts->pluck('videos')->flatten()->map(function ($video) {
                    return asset('images/posts/' . $video->video);
                });
                $media['videos'] = $videos;
            }
            return response()->json($media);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 500], 500);
        }
    }


    public function getMyMentionedPosts(Request $request, $userId)
    {
        try {
            $user = User::find($userId);
            $posts = $this->whereHas('comments', function ($query) use ($user) {
                $query->where('mention_email', $user->email);
            })->get();
            $collection = PostResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
