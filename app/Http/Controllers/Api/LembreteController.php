<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Historicos;
use App\Models\Lembretes;
use App\Models\TitularesSecundarios;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LembreteController extends Controller
{
    public function lembretesSemana()
    {
        $lembretes = Lembretes::select(
            'lembretes.id',
            'lembrete_dia_semana.id as id_notificacao',
            'lembretes.nome',
            'lembretes.descricao',
            'dia_semana.nome as dia_semana',
            'lembretes.hora',
        )
            ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
            ->join('lembrete_dia_semana', 'lembrete_dia_semana.id_lembrete', 'lembretes.id')
            ->join('dia_semana', 'dia_semana.id', 'lembrete_dia_semana.id_dia_semana')
//            ->where('lembrete_usuario.id_destinatario', Auth::user()->id)
            ->where('lembrete_usuario.id_destinatario', 4)
            ->get()
            ->toArray();

        return $lembretes;
    }

    public function processarNotificacoes(array $lembretes)
    {
        try {
            $diasDaSemanaMap = [
                'Domingo' => 0,
                'Segunda-feira' => 1,
                'Terça-feira' => 2,
                'Quarta-feira' => 3,
                'Quinta-feira' => 4,
                'Sexta-feira' => 5,
                'Sábado' => 6,
            ];

            $notificacoes = [];

            foreach ($lembretes as $lembrete) {
                $diaSem = $lembrete['dia_semana'];
                $horaLembrete = Carbon::createFromFormat('H:i:s', $lembrete['hora']);
                $diaDaSemana = $diasDaSemanaMap[$diaSem] ?? null;

                if ($diaDaSemana === null) {
                    continue;
                }

                $hoje = Carbon::now();
                $diaAtual = $hoje->dayOfWeek;

                $diasParaNotificacao = ($diaDaSemana - $diaAtual + 7) % 7;

                if ($diasParaNotificacao === 0) {
                    if ($hoje->hour > $horaLembrete->hour ||
                        ($hoje->hour == $horaLembrete->hour && $hoje->minute >= $horaLembrete->minute)) {
                        $diasParaNotificacao = 7;
                    }
                }

                $dataNotificacao = $hoje->copy()->addDays($diasParaNotificacao)->setTime($horaLembrete->hour, $horaLembrete->minute);

                $diferencaSegundos = $hoje->diffInSeconds($dataNotificacao);

                $notificacao = (object)[
                    'dia_semana'         => $diaSem,
                    'id_lembrete'        => $lembrete['id'],
                    'nome'               => $lembrete['nome'],
                    'descricao'          => $lembrete['descricao'],
                    'data_notificacao'   => $dataNotificacao->format('Y-m-d H:i:s'),
                    'diferenca_segundos' => $diferencaSegundos,
                    'confirmado'         => false,
                    'id_notificacao'     => $lembrete['id_notificacao']
                ];

                $notificacoes[] = $notificacao;
            }

            return $notificacoes;

        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function recebeConfirmacao(Request $request)
    {
        try {
            $validacao = Lembretes::select()
                ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
                ->where('lembrete_usuario.id_destinatario', Auth::user()->id)
                ->where('lembretes.id', $request->id_lembrete)
                ->first();

//        if (is_null($validacao)) {
//            return response()->json([
//                'status'       => 'error',
//                'message'      => 'Lembrete não pertence ao usuário',
//            ]);
//        }

            if (
                is_null($request->dia_semana) ||
                is_null($request->data_notificacao) ||
                is_null($request->data_confirmacao) ||
                is_null($request->confirmado)
            ) {
                return response()->json([
                    'status'       => 'error',
                    'message'      => 'Backend não recebeu todas as informações',
                ]);
            }

            $historico                   = New Historicos;
            $historico->dia_semana       = $request->dia_semana;
            $historico->confirmado       = $request->confirmado;
            $historico->data_notificacao = $request->data_notificacao;
            $historico->data_confirmacao = $request->data_confirmacao;
            $historico->id_notificacao   = $request->id_notificacao;
            $historico->id_usuario       = Auth::user()->id;
            $historico->save();

            return response()->json([
                'status'       => 'sucess',
                'message'      => 'Histórico salvo com sucesso',
                'data'         => [
                    'historico' => $historico
                ],
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status'       => 'error',
                'message'      => $exception->getMessage(),
            ]);
        }
    }

    public function relatorio(Request $request)
    {
        if ( is_null($request->mes) || is_null($request->id_secundario) ) {
            return response()->json([
                'status'       => 'error',
                'message'      => 'Backend não recebeu todas as informações',
            ]);
        }

        $validacaoRelacao = TitularesSecundarios::select()
            ->where('id_titular', Auth::user()->id)
            ->where('id_secundario', $request->id_secundario)
            ->first();

        if (is_null($validacaoRelacao) || Auth::user()->tipo <> 'A') {
            return response()->json([
                'status'       => 'error',
                'message'      => 'Você não ter permissão a acessar essas informações',
            ]);
        }

        $relatorio = Historicos::select(
                'historicos.id',
                'lembretes.nome',
                'historicos.data_notificacao',
                'historicos.data_confirmacao',
            )
            ->join('lembrete_dia_semana', 'lembrete_dia_semana.id', 'historicos.id_notificacao')
            ->join('lembretes', 'lembretes.id', 'lembrete_dia_semana.id_lembrete')
            ->whereMonth('historicos.data_notificacao', $request->mes)
//            ->where('lembrete_usuario.id_destinatario', $request->id_secundario)
            ->where('historicos.id_usuario', 2)
            ->get();

        return response()->json([
            'status'       => 'sucess',
            'message'      => 'Relatorio tirado com sucesso',
            'data'         => [
                'relatorio' => $relatorio
            ],
        ]);
    }
}
