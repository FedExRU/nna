<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\{
    JsonResponse,
    Request
};
use Illuminate\Support\Facades\{
    Auth,
    Validator
};

class AuthController extends Controller
{
    private CONST AUTH_HEADER = 'Authorization';

    /**
     * Регистрация нового пользователя в системе.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 442);
        }

        try {
            $user = new User();
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->name = $request->get('name');
            $user->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    /**
     * Авторизация пользователя в системе по email'у и password'у.
     * Возвращает токен авторизации в заголовке Authorization.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        /*
         * Собираем полномочия для авторизации из запроса.
         */
        $credentials = $request->only([
            'email',
            'password',
        ]);

        if ($token = auth()->attempt($credentials)) {
            return response()
                ->json([
                    'status' => 'success',
                ], 200)
                ->header(self::AUTH_HEADER, $token);
        }
        return response()->json([
            'error' => 'login_error',
        ], 401);
    }

    /**
     * Выход из системы путём отключения токена пользователя.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Выход из системы выполнен успешно!',
        ], 200);
    }

    /**
     * Информация по текущему авторизованному пользователю.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => User::find(Auth::user()->id),
        ]);
    }

    /**
     * Продлить токен пользователя.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        if ($token = Auth::guard()->refresh()) {
            return response()
                ->json([
                    'status' => 'success',
                    ], 200
                )
                ->header(self::AUTH_HEADER, $token);
        }
        return response()
            ->json([
                'error' => 'refresh_token_error',
            ], 401);
    }
}
