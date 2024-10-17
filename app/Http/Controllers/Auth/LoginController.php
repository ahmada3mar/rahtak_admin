<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Sadad;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'mobile' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if (!$token = auth()->attempt($data)) {
            return response()->json(['message' => 'mobile or password are incorrect'], 422);
        }

        return $this->createNewToken($token);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'mobile' => 'required|exists:users,mobile',
        ]);

        $user = User::whereMobile($data['mobile'])->first();
        return $user->resetPassword();
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|min:6|confirmed',
            'api_token' => 'required'
        ]);

        $user = User::whereApiToken($data['api_token'])->firstOrFail();
        $user->password = \bcrypt($data['password']);
        $user->api_token = null;
        $user->save();

        if (!$token = auth()->attempt(['mobile' => $user->mobile, 'password' => $data['password']])) {
            return response()->json(['error' => 'invalid invitation link'], 422);
        }

        return $this->createNewToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (auth()->user())
            auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();
            return $this->createNewToken($token);
        } catch (\Throwable $th) {
            return \response('Unauthenticated User', 401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        $user = auth()->user();
        return response()->json(
            \array_merge([
            'user' => [
                'id' => $user->id,
                'fullName' =>  $user->name,
                'username' =>  $user->email,
                'mobile' =>  $user->mobile,
                'permissions' => $user->getAllPermissions()->map(function ($value) {
                    return [
                        'name' => $value->name,
                        'group' => $value->group,
                    ];
                }),
            ],
            'token' => $token,
        ] , (new Sadad)->finance()->json()));
    }

    public function user()
    {
        $user = auth()->user();
        return \array_merge(['data' => new UserResource($user)] ,(new Sadad)->finance()->json() );
    }
}
