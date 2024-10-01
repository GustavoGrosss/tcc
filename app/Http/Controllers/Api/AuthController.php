<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lembretes;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Credenciais inválidas, favor tentar novamente ou clique em esqueci minha senha'
                ], 401);
            }

            $user = User::select()
                ->where('email', $request->email)
                ->first();

//        $lembretes = (new LembreteController)->lembretesSemana();

            return response()->json([
                'status'       => 'sucess',
                'message'      => 'Login efetuado com sucesso.',
                'data'         => [
                    'access_token' => $token,
                    'usuario'   => $user,
//                'lembretes' => $lembretes
                ],
            ]);

        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status'  => 'sucess',
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function teste(){
        return response()->json([
            'data' => User::all(),
        ]);
    }
}
