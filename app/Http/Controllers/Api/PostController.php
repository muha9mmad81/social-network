<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $post, $postComment, $postLike, $commentLike;
    function __construct(Post $post, PostComment $postComment, PostLike $postLike, CommentLike $commentLike) {
        $this->post = $post;
        $this->postComment = $postComment;
        $this->postLike = $postLike;
        $this->commentLike = $commentLike;
    }

    public function createPost(Request $request) {
        return $this->post->addPost($request);
    }

    public function getMyPosts(Request $request) {
        return $this->post->getAllMyPosts($request);
    }

    public function getAllPosts(Request $request) {
        return $this->post->getAllPosts($request);
    }

    public function addPostComment(Request $request) {
        return $this->postComment->addPostComment($request);
    }

    public function addLikesAndDislikesToPosts(Request $request) {
        return $this->postLike->addLikesAndDislikesToPosts($request);
    }

    public function addLikesAndDislikesToComments(Request $request) {
        return $this->commentLike->addLikesAndDislikesToComments($request);
    }

    public function getSinglePost(Request $request, $id) {
        return $this->post->getPostById($request, $id);
    }
}
