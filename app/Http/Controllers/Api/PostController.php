<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostFavouriteRequest;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostFavourite;
use App\Models\PostLike;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $post, $postComment, $postLike, $commentLike, $favourite;
    function __construct(Post $post, PostComment $postComment, PostLike $postLike, CommentLike $commentLike, PostFavourite $favourite)
    {
        $this->post = $post;
        $this->postComment = $postComment;
        $this->postLike = $postLike;
        $this->commentLike = $commentLike;
        $this->favourite = $favourite;
    }

    public function createPost(Request $request)
    {
        return $this->post->addPost($request);
    }

    public function deletePost(Request $request, $postId)
    {
        return $this->post->deletePost($request, $postId);
    }

    public function getMyPosts(Request $request)
    {
        return $this->post->getAllMyPosts($request);
    }

    public function getAllPosts(Request $request)
    {
        return $this->post->getAllPosts($request);
    }

    public function getAllMyGroupPosts(Request $request)
    {
        return $this->post->getAllMyGroupPosts($request);
    }

    public function getAllGroupPosts(Request $request)
    {
        return $this->post->getAllGroupPosts($request);
    }

    public function getPostAccordingToGroup(Request $request)
    {
        return $this->post->getPostAccordingToGroup($request);
    }

    public function addPostComment(Request $request)
    {
        return $this->postComment->addPostComment($request);
    }

    public function editPostComment(Request $request, $commentId)
    {
        return $this->postComment->editPostComment($request, $commentId);
    }

    public function deletePostComment(Request $request, $commentId)
    {
        return $this->postComment->deletePostComment($request, $commentId);
    }

    public function addLikesAndDislikesToPosts(Request $request)
    {
        return $this->postLike->addLikesAndDislikesToPosts($request);
    }

    public function addLikesAndDislikesToComments(Request $request)
    {
        return $this->commentLike->addLikesAndDislikesToComments($request);
    }

    public function getSinglePost(Request $request, $id)
    {
        return $this->post->getPostById($request, $id);
    }

    public function getUserMedia(Request $request, $userId)
    {
        return $this->post->getUserMedia($request, $userId);
    }

    public function createOrRemovePostFavourite(PostFavouriteRequest $request)
    {
        return $this->favourite->createOrRemovePostFavourite($request);
    }

    public function getMyFavouritePosts(Request $request)
    {
        return $this->favourite->getMyFavouritePosts($request);
    }


    //

    public function getUserPosts(Request $request, $userId)
    {
        return $this->post->getAllUserPosts($request, $userId);
    }

    public function getUserMediaWithoutAuthentication(Request $request, $userId)
    {
        return $this->post->getUserMediaWithoutAuthentication($request, $userId);
    }

    public function getUserFavouritePosts(Request $request, $userId)
    {
        return $this->favourite->getUserFavouritePosts($request, $userId);
    }

    public function getAllUserGroupPosts(Request $request, $userId)
    {
        return $this->post->getAllUserGroupPosts($request, $userId);
    }

    public function getMyMentionedPosts(Request $request, $userId)
    {
        return $this->post->getMyMentionedPosts($request, $userId);
    }

    public function sharePost(Request $request, $postId)
    {
        return $this->post->sharePost($request, $postId);
    }
}
