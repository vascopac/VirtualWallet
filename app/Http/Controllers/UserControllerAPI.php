<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\StoreUserRequest;
use Hash;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
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
        return response()->json(new UserResource($user), 201);
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
}
