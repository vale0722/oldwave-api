<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): array
    {
        $data = $request->validated();

        $request = array_merge($data, [
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(10),
        ]);

        $user = User::create($request);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->api(
            Status::OK,
            compact('user', 'token')
        );
    }

    public function login(LoginRequest $request): array
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->api(
                Status::ERROR,
                ['message' => 'Invalid login details']
            );
        }

        $user = User::where('email', $request->get('email'))->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->api(
            Status::OK,
            compact('user', 'token')
        );
    }

    public function logout(Request $request): JsonResponse
    {
        if (method_exists(auth()->user()->currentAccessToken(), 'delete')) {
            auth()->user()->currentAccessToken()->delete();
        }
        auth()->guard('web')->logout();

        return response()->json(['message' => 'You have been successfully logged out!']);
    }

    public function redirect(string $driver): string
    {
        return Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
    }

    public function callback(string $driver): array
    {
        try {
            $driverUser = Socialite::driver($driver)->stateless()->user();
        } catch (\Throwable $exception) {
            return response()->api(Status::ERROR, 'Las credeciales son invalidas');
        }

        /** @var User $user */
        $user = User::updateOrCreate([
            'email' => $driverUser->getEmail(),
        ], [
            'email_verified_at' => now(),
            'name' => $driverUser->getName(),
            'password' => Hash::make(Str::random()),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->api(
            Status::OK,
            compact('user', 'token')
        );
    }
}
