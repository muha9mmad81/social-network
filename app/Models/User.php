<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\UserResource;
use App\Notifications\ForgotPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function user_information()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function friendRequestsSent()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    // Friend Requests Received
    public function friendRequestsReceived()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    // Friends List
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserById($id)
    {
        return $this->find($id);
    }

    public function getUserByEmailOrUsername($value)
    {
        return $this->where('email', $value)
            ->orWhere('username', $value)
            ->first();
    }

    public function getPendingFriendRequests()
    {
        return $this->friendRequestsReceived()->where('status', 'pending')->get();
    }


    public function addUser(Request $request)
    {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->password = Hash::make($request->blue_key);
        $this->username = $request->username;
        $this->email_verified_at = now();
        $this->save();

        return response()->json(['data' => new UserResource($this), 'message' => 'User registered successfully', 'status' => 200], 200);
    }

    public function loginUser(Request $request)
    {
        $user = $this->getUserByEmailOrUsername($request->email);
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        // dd($loginField, $user);
        if (!$user || !Hash::check($request->blue_key, $user->password)) {
            return response()->json(['data' => null, 'message' => 'Provided credentials is incorrect', 'status' => 401], 401);
        }

        if (Auth::attempt([$loginField => $request->email, 'password' => $request->blue_key])) {
            $checkLastAttemptedUser = Auth::getLastAttempted();
            Auth::login($checkLastAttemptedUser);
            $token = Auth::user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token, 'data' => new UserResource(Auth::user()), 'message' => 'User logged in successfully', 'status' => 200], 200);
        }

        return response()->json(['data' => null, 'message' => 'Invalid details', 'status' => 401], 401);
    }

    public function forgotPassword(Request $request)
    {
        $randomToken = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $token = generateUniqueCode($randomToken, PasswordReset::class);
        $user = $this->getUserByEmailOrUsername($request->email);

        if (!$user) {
            return response()->json(['message' => 'User not found.', 'status' => 404], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            [
                'email'         => $request->email,
            ],
            [
                'token'         => $token,
                'created_at'    => now()
            ]
        );
        $user->notify(new ForgotPasswordNotification($user, $token));
        return response()->json(['message' => 'Code has been sent to your email, please verify.', 'status' => 200], 200);
    }

    public function resetPassword(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if ($passwordReset) {
            $user = $this->getUserByEmailOrUsername($passwordReset->email);
            $user->password = Hash::make($request->blue_key);
            $user->update();

            $passwordReset->delete();

            return response()->json(['data' => new UserResource($user), 'message' => 'Your Blue Key has been reset.', 'status' => 200], 200);
        } else {
            return response()->json(['data' => null, 'message' => 'Code is invalid.', 'status' => 401], 401);
        }
    }

    public function editUserProfile(Request $request)
    {
        try {
            $user = $this->getUserById(auth()->user()->id);

            $user->name = $request->name ?? $user->name;
            $user->update();

            $userInfo = UserInformation::firstOrNew(['user_id' => $user->id]);

            $userInfo->dob = $request->dob ?? $userInfo->dob;
            $userInfo->dob_visibility = $request->dob_visibility ?? $userInfo->dob_visibility;
            $userInfo->gender = $request->gender ?? $userInfo->gender;
            $userInfo->gender_visibility = $request->gender_visibility ?? $userInfo->gender_visibility;
            $userInfo->city = $request->city ?? $userInfo->city;
            $userInfo->city_visibility = $request->city_visibility ?? $userInfo->city_visibility;
            $userInfo->country = $request->country ?? $userInfo->country;
            $userInfo->country_visibility = $request->country_visibility ?? $userInfo->country_visibility;
            $userInfo->about = $request->about ?? $userInfo->about;
            $userInfo->about_visibility = $request->about_visibility ?? $userInfo->about_visibility;
            $userInfo->link1 = $request->link1 ?? $userInfo->link1;
            $userInfo->link1_visibility = $request->link1_visibility ?? $userInfo->link1_visibility;
            $userInfo->link2 = $request->link2 ?? $userInfo->link2;
            $userInfo->link2_visibility = $request->link2_visibility ?? $userInfo->link2_visibility;

            $userInfo->save();


            return response()->json(['data' => new UserResource($user), 'message' => 'Profile updated successfully', 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function updateProfilePhoto(Request $request)
    {
        try {
            if ($request->profile_image) {
                $user = $this->getUserById(auth()->user()->id);

                $fileName = $request->profile_image->getClientOriginalName();
                $file = saveFile($request->profile_image, 'images/users', $fileName);

                UserInformation::updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'profile_image' => $file['name'],
                    ]
                );

                return response()->json(['data' => new UserResource($user), 'message' => 'Profile Image updated successfully', 'status' => 200], 200);
            }

            return response()->json(['message' => 'Porfile Image is required.', 'status' => 500], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function updateCoverPhoto(Request $request)
    {
        try {
            if ($request->cover_image) {
                $user = $this->getUserById(auth()->user()->id);

                $fileName = $request->cover_image->getClientOriginalName();
                $file = saveFile($request->cover_image, 'images/users', $fileName);

                UserInformation::updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'cover_image' => $file['name'],
                    ]
                );

                return response()->json(['data' => new UserResource($user), 'message' => 'Cover Image updated successfully', 'status' => 200], 200);
            }

            return response()->json(['message' => 'Cover Image is required.', 'status' => 500], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllUsers(Request $request)
    {
        try {
            $users = $this->where('id', '!=', auth()->user()->id)
                ->orderByDesc('id')
                ->get();
            $collection = UserResource::collection($users);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function showFriendRequests(Request $request)
    {
        try {
            $pendingRequests = auth()->user()->getPendingFriendRequests();
            $collection = FriendRequestResource::collection($pendingRequests);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
