<?php

namespace App\Http\Controllers;

use App\Events\ExampleEvent;
use App\Models\Post;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Log;

class UserController extends Controller
{
    //
    public function showCorrectHomePage()
    {
        if (auth()->check()) {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            return view("homepage-logged", ['posts' => $user->feedPosts()->latest()->with('user')->paginate(4)]);
        } else {
            return view("homepage");
        }
    }
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);
        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            event(new ExampleEvent(['username' => $user->username, 'action' => 'login']));
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/')->with('failure', 'Invaild login.');
        }
    }
    public function logout()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        auth()->logout();
        event(new ExampleEvent(['username' => $user->username, 'action' => 'logout']));
        return redirect('/')->with('success', 'You have successfully logged out.');
    }
    public function register(Request $request)
    {
        // data imported from database
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'password' => ['required', 'min:8', 'confirmed'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating an account!');
    }

    private function profileShare($user)
    {
        $currentFollowing = 0;
        if (auth()->check()) {
            $currentFollowing = Follower::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentFollowing' => $currentFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followingCount' => $user->following()->count()]);
    }
    public function profile(User $user)
    {
        $thispost =  $user->posts()->latest()->with('user')->get();
        // return $thispost;
        $this->profileShare($user);
        return view('profile-posts', ['posts' => $thispost]);
    }
    public function profileRaw(User $user)
    {
        $thispost =  $user->posts()->latest()->with('user')->get();
        // return $thispost;
        $this->profileShare($user);
        return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $thispost])->render(), 'docTitle' => $user->username . "'s Profile" ]);
    }
    public function profileFollowers(User $user)
    {
        $this->profileShare($user);
        // return $user->followers()->latest()->get();
        // $followers = Follower::with('userDoingtheFollowing')->where('user_id', $user->id)->latest()->get();
        // return view('profile-followers', ['followers' => $followers]);
        return view('profile-followers', ['followers' => $user->followers()->latest()->with('userDoingtheFollowing')->get()]);
    }
    public function profileFollowersRaw(User $user)
    {
        $this->profileShare($user);
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->with('userDoingtheFollowing')->get()])->render(), 'docTitle' => $user->username . "'s Followers" ]);

    }

    public function profileFollowing(User $user)
    {
        $this->profileShare($user);
        $following = Follower::with('userBeingFollowed')->where('user_id', $user->id)->latest()->get();

        return view('profile-following', ['following' => $following]);
    }
    public function profileFollowingRaw(User $user)
    {
        $this->profileShare($user);
        $following = Follower::with('userBeingFollowed')->where('user_id', $user->id)->latest()->get();
        // Log::info('called');
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $following])->render(), 'docTitle' => $user->username . "'s Followings" ]);
    }
    public function showAvatarForm()
    {
        return view('avatar-form');
    }
    public function storeAvatarForm(Request $request)
    {

        // $request->file('avatar')->store('test123');
        $request->validate([
            'avatar' => 'required|image|max:3000',
        ]);
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $filename = $user->id . "-" . uniqid() . ".jpg";
        // $request->file('avatar')->store('public/avatars');
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('avatar'));
        $imageData = $image->cover(120, 120)->toJpeg();
        Storage::put('public/avatars/' . $filename, $imageData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != '/fallback-avatar.jpg') {
            Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
        }

        return back()->with('success', 'Success change the Avatar!');
    }
}
