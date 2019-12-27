<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Wallet;
use App\StoreUserRequest;
use Hash;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        return UserResource::collection(User::all());    
    }

    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'nif' => ['required', 'numeric', 'digits:9'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg']
        ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->password);

        if($request->photo != null){
            $imageName = time().'.'.$request->photo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('fotos', $request->photo, $imageName);
            $user->photo = $imageName;
        };
        $user->save();
        $wallet = new Wallet();
        $wallet->id = $user->id;
        $wallet->email = $user->email;
        $wallet->balance = 0;
        $wallet->save();
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nif' => ['required', 'numeric', 'digits:9'],
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function editPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $new_password = bcrypt($request['newPassword']);
        if (Hash::check($request['oldPassword'], $user->password)) {
            $user->password = $new_password;
            $user->update();
            return new UserResource($user, 200);
        }
        return response()->json(null, 403);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        $user = User::findOrFail($request->id);
        $imageName = time().'.'.$request->photo->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('fotos', $request->photo, $imageName);
        $user->photo = $imageName;
        $user->update();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'type' => ['required', 'string'],
        ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->password);
        if($request->photo != null){
            $imageName = time().'.'.$request->photo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('fotos', $request->photo, $imageName);
            $user->photo = $imageName;
        };
        $user->save();
        return new UserResource($user);
    }
}
