<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use Auth;

class UsersController extends Controller
{
   /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'roles' => empty($request->roles) ? 'user' : $request->roles
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

     /**
     * Get user by id
     *
     * @param  [int] id
     * @return [string] message
     */
    public function getUsers()
	{
		return User::all();
    }

     /**
     * Get user by id
     *
     * @param  [int] id
     * @return [string] message
     */
    public function getUserById($id)
	{
		return User::find($id);
    }
    
   /**
     * Update user
     *
     * @param  [string] name
     * @param  [string] roles
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function updateUser($id, Request $request)
	{
        $request->validate([
            'name' => 'required|string',
            'roles' => 'required|string',
            'password' => 'confirmed'            
        ]);
        
		$user = $this->getUserById($id);

        if (empty($user)) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

        // normal users can update only own info, unless admin
        if ($user->id != Auth::user()->id && !$user->hasRole('admin'))
        {
            return response()->json([
                'message' => 'Access Denied!'
            ], 403);
        }

        if (!empty($request->password) && !empty($request->password_confirmation))
        {
            $user->password = bcrypt($request->password);
        }
        
        $user->name = $request->name;
		$user->roles = $request->roles;

		$user->save();

		return $user;
	}
  
    /**
     * Change Password
     *
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function changePassword(Request $request)
	{
		// $this->authorisationService->assertRole('admin');

        $request->validate([            
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

		$user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is invalid!'
            ], 400);
        }

		$user->password = bcrypt($request->password);

		$user->save();

		return response()->json([
            'message' => 'Password has been changed.'
        ], 200);
    }
    
    /**
     * Delete user
     *
     * @param  [string] name
     * @return [string] message
     */
    public function deleteUser($id)
	{
		// $this->authorisationService->assertRole('admin');
        
		$user = $this->getUserById($id);

        if (empty($user)) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

		$user->delete();

		return response()->noContent();
    }
    
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles,
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getCurrentUser(Request $request)
    {
        return response()->json($request->user());
    }
}
