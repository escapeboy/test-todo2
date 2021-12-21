<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Todo\Users\User;
use Todo\Users\UsersRepository;

final class AuthController
{
    public function __construct(private UsersRepository $usersRepository)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);
        $credentials = $request->only(['email', 'password']);

        /** @var User $user */
        $user = $this->authAttempt($credentials);
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $tokenResult = $user->createToken('Personal Access Token', ['*']);
        $token = $tokenResult->token;
        if ($request->remember) {
            $token->expires_at = Carbon::now()->addWeek();
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user_data' => $user->toArray(),
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logout(): JsonResponse
    {
        Auth::user()?->token()?->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:Todo\Users\User'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = new User();
            $user->setId(Str::uuid()->toString());
            $user->setName($request->get('name'));
            $user->setEmail($request->get('email'));
            $user->setPassword(Hash::make($request->get('password')));

            app(UsersRepository::class)->persist($user);
            app('em')->flush();

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeek();
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'user_data' => $user->toArray(),
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    private function authAttempt(array $credentials): bool|User
    {
        $email = Arr::get($credentials, 'email');
        $password = Arr::get($credentials, 'password');
        $user = $this->usersRepository->findOneBy(['email' => $email]);
        if (!$user instanceof User) {
            return false;
        }
        if (!Hash::check($password, $user->getPassword())) {
            return false;
        }

        return $user;
    }
}
