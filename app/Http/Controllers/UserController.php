<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\{
    JsonResponse,
    Request
};

class UserController extends Controller
{
    /**
     * Получить список всех пользователей.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()
            ->json([
                'success' => true,
                'data' => User::all(),
            ]);
    }

    /**
     * Информация о пользователе по его id.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function user(Request $request, int $id)
    {
        return response()
            ->json([
                'success' => true,
                'data' => User::find($id),
            ]);
    }
}
