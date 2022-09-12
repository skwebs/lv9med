<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            "success" => true,
            "attributes" => $users,
            "count" => $users->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3",
                "email" => "required|email|unique:users",
                "password" => "required|min:8|max:20|confirmed"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ]);
        }

        //create company record
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        // $user = User::create($request->only(["name", "email", "password"]));

        if ($user) {
            return response()->json([
                "success" => true,
                "message" => "User created successfully.",
                "attributes" => $user,
                "token" => $user->createToken("API TOKEN")->plainTextToken
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Requested user not found in our database."
            ]);
        }
        return response()->json([
            "success" => true,
            "attributes" => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Requested user not found in our database."
            ]);
        }
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3",
                "email" => "required|email|unique:users,email," . $id . ",id",
                "password" => "required|min:8|max:20|confirmed"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ]);
        }

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        if ($user) {
            return response()->json([
                "success" => true,
                "message" => "User data updated successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "User data couldn't updated."
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Requested user not found in our database."
            ]);
        }

        $user->delete();
        return response()->json([
            "success" => true,
            "message" => "User deleted successfully."
        ]);
    }

    public function trashed()
    {
        $trashed = User::onlyTrashed();
        if (!$trashed->count() > 0) {
            return response()->json([
                "success" => false,
                "message" => "Trashed records did not found."
            ]);
        }
        return response()->json([
            "success" => true,
            "attributes" => $trashed->get(),
            "count" => $trashed->count()
        ]);
    }

    public function restore($id)
    {
        $trashed = User::onlyTrashed()->whereId($id);
        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "Data restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Requested trashed record did not found."
            ]);
        }
    }

    public function restoreAll()
    {
        $trashed = User::onlyTrashed();

        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "All records restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No record found for restore."
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "email" => "required|email",
                    "password" => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                ]);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    "status" => false,
                    "message" => "User login failed.",
                ]);
            }
            $user = User::where("email", $request->email)->first();
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully.",
                "token" => $user->createToken("API TOKEN")->plainTextToken
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
