<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lembretes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LembreteController extends Controller
{
    public function lembretesSemana()
    {
        $lembretes = Lembretes::select(
            'lembretes.nome',
            'lembretes.descricao',
            'lembretes.confirmado',
            'dia_semana.nome as dia_semana',
            'lembretes.hora',
        )
            ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
            ->join('dia_semana', 'dia_semana.id', 'lembretes.id_dia_semana')
            ->where('lembrete_usuario.id_destinatario', Auth::user()->id)
            ->get();

        $resultados = [];

        $agora = Carbon::now();

        foreach ($lembretes as $lembrete) {
            $diaDaSemana = '';

            switch ($lembrete->dia_semana) {
                case 'Domingo':
                    {
                        $diaDaSemana = '0';
                    }
                    break;
                case 'Segunda-feira':
                    {
                        $diaDaSemana = '1';
                    }
                    break;
                case 'Terça-feira':
                    {
                        $diaDaSemana = '2';
                    }
                    break;
                case 'Quarta-feira':
                    {
                        $diaDaSemana = '3';
                    }
                    break;
                case 'Quinta-feira':
                    {
                        $diaDaSemana = '4';
                    }
                    break;
                case 'Sexta-feira':
                    {
                        $diaDaSemana = '5';
                    }
                    break;
                case 'Sábado':
                    {
                        $diaDaSemana = '6';
                    }
                    break;
            }

            $hora = $lembrete->hora; // A hora deve estar no formato HH:mm

            // Criar um objeto Carbon para o próximo lembrete
            $proximaOcorrencia = Carbon::parse($hora);

            // Definir o dia da próxima ocorrência
            $proximaOcorrencia->next($diaDaSemana);

            // Verificar se o lembrete é para hoje e ainda não passou
            if ($diaDaSemana === $agora->dayOfWeek && $agora->lt($proximaOcorrencia)) {
                // Se ainda não passou, usamos a data de hoje
                $proximaOcorrencia = $agora->copy()->setTimeFrom($proximaOcorrencia);
            }

            // Calcular a diferença em segundos
            $tempoFaltando = Carbon::now()->diffInSeconds($proximaOcorrencia);

            // Adicionar ao array de resultados
            $resultados[] = [
                'nome'               => $lembrete->nome,
                'descricao'          => $lembrete->descricao,
                'dia_semana'         => $diaDaSemana,
                'hora'               => $hora,
                'proxima_ocorrencia' => $proximaOcorrencia->toDateTimeString(),
                'tempo_faltando'     => $tempoFaltando,
            ];
        }

        return $resultados;
    }
}
