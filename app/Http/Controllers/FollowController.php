<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //
    public function createFollow(User $user){
        // cannot follow yourself
        if($user->id == auth()->user()->id){
            return back()->with("failure","You cannot follow yourself!");
        }
        // you cannot follow someone already following
        $existCheck = Follower::where([["user_id",'=',auth()->user()->id],["followeduser",'=',$user->id]])->count();
        if($existCheck){
            return back()->with('failure','You are already following that user!');
        }

        $newFollow = new Follower;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

       return back()->with('success','User successfully followed');
    }

    public function removeFollow(User $user){
        Follower::where([['user_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->delete();
        return back()->with('success','User successfully Unfollowed');
    }
}
