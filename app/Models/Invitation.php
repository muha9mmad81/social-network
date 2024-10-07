<?php

namespace App\Models;

use App\Http\Resources\InvitationResource;
use App\Notifications\InvitationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class Invitation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_id', 'email', 'status', 'token'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sendInvitation(Request $request)
    {
        try {
            $randomToken = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $token = generateUniqueCode($randomToken, Invitation::class);

            $userId = auth()->user()->id;

            $this->user_id = $userId;
            $this->email = $request->email;
            $this->status = 'Pending';
            $this->token = $token;
            $this->save();

            Notification::route('mail', $request->email)
                ->notify(new InvitationNotification($this, $request->email, auth()->user()));

            // $this->notify(new InvitationNotification($this, $reciever, auth()->user()));

            return response()->json(['status' => 200, 'message' => 'Invitation sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function updateInvitationStatus(Request $request)
    {
        try {
            $invitation = $this->where('token', $request->token)->first();

            if ($invitation) {
                $status = $request->status ?? 'Accepted'; // Get the status or default to 'Accepted'
                $invitation->status = $status; // Assign the status to the invitation
                $invitation->update(); // Update the model in the database

                return response()->json(['status' => 200, 'message' => 'You have ' . $status . ' the invitation.'], 200);
            } else {
                return response()->json(['status' => 401, 'message' => 'Provide a valid token'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getMyInvitations(Request $request)
    {
        try {
            $invitations = $this->where('user_id', auth()->user()->id)
                ->orderByDesc('id')
                ->get();
            $collection = InvitationResource::collection($invitations);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
