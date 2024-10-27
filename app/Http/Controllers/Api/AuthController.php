<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lembretes;
use App\Models\TitularesSecundarios;
use App\Models\User;
use Carbon\Carbon;
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

            $lembretes = (new LembreteController)->processarNotificacoes((new LembreteController)->lembretesSemana());

            $secundarios = [];

//            if ($user->tipo == 'T') {
                $secundarios = TitularesSecundarios::select('usuarios.id', 'usuarios.name')
                    ->join('usuarios', 'usuarios.id', 'titulares_secundarios.id_secundario')
                    ->where('titulares_secundarios.id_titular', 4)
                    ->get();
//            }

            return response()->json([
                'status'       => 'sucess',
                'message'      => 'Login efetuado com sucesso.',
                'data'         => [
                    'access_token' => $token,
                    'usuario'      => $user,
                    'lembretes'    => $lembretes,
                    'secundarios'  => $secundarios
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
}
