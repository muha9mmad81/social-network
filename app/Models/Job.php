<?php

namespace App\Models;

use App\Http\Resources\JobResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Job extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'title', 'description', 'location', 'remote', 'job_type', 'application_email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postJob(Request $request) {
        try {
            $this->user_id = auth()->user()->id;
            $this->title = $request->title ?? null;
            $this->description = $request->description ?? null;
            $this->location = $request->location ?? null;
            $this->remote = $request->remote ?? 0;
            $this->job_type = $request->job_type ?? null;
            $this->application_email = $request->application_email ?? null;
            $this->save();

            $image = null;
            if($request->logo){
                $fileName = $request->logo->getClientOriginalName();
                $file = saveFile($request->logo, 'images/jobs', $fileName);
                $image = $file['name'];
            }

            UserCompany::updateOrCreate(
                [
                    'user_id' => auth()->user()->id
                ],
                [
                    'name' => $request->company_name,
                    'website' => $request->website,
                    'tagline' => $request->tagline,
                    'video' => $request->video,
                    'twitter_username' => $request->twitter_username,
                    'logo' => $image,
                ]
            );

            return response()->json(['status' => 200, 'message' => 'Job has been created', 'data' => new JobResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getMyJobs(Request $request)
    {
        try {
            $user = auth()->user();
            $posts = $this->where('user_id', $user->id)->orderByDesc('id')->get();
            $collection = JobResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllJobs(Request $request)
    {
        try {
            $posts = $this->orderByDesc('id')->get();
            $collection = JobResource::collection($posts);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getJobById(Request $request, $jobId)
    {
        try {
            $post = $this->find($jobId);
            $data = new JobResource($post);

            return response()->json(['status' => 200, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
