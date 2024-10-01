<?php

namespace App\Models;

use App\Jobs\ProcessarNotificacaoJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Lembretes extends Model
{
    use HasFactory;

    protected $table = 'lembretes';

    protected $fillable = [
        'nome',
        'descricao',
        'hora',
        'id_cadastrante',
        'id_dia_semana',
    ];

    public function cadastrante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_cadastrante');
    }

    public function destinatarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lembrete_usuario', 'id_lembrete', 'id_destinatario');
    }

    public function dia_semana(): BelongsToMany
    {
        return $this->belongsToMany(DiaSemana::class, 'lembrete_dia_semana', 'id_lembrete', 'id_dia_semana');
    }

    protected static function booted()
    {
        static::saved(function ($lembrete) {
            ProcessarNotificacaoJob::dispatch($lembrete->id);
        });
    }

    protected static function processarNotificacao($lembrete)
    {
        try {
            $lembrete->load('dia_semana', 'destinatarios');

            $diasSemana    = $lembrete->dia_semana()->pluck('nome')->toArray();
            $destinatarios = $lembrete->destinatarios()->pluck('email')->toArray();

            foreach ($diasSemana as $dia)
            {
                switch ($dia) {
                    case 'Domingo':
                        $diaDaSemana = 0;
                        break;
                    case 'Segunda-feira':
                        $diaDaSemana = 1;
                        break;
                    case 'Terça-feira':
                        $diaDaSemana = 2;
                        break;
                    case 'Quarta-feira':
                        $diaDaSemana = 3;
                        break;
                    case 'Quinta-feira':
                        $diaDaSemana = 4;
                        break;
                    case 'Sexta-feira':
                        $diaDaSemana = 5;
                        break;
                    case 'Sábado':
                        $diaDaSemana = 6;
                        break;
                }

                $hoje = Carbon::now();
                $semanaAtual = $hoje->copy()->startOfWeek(); // Define o início da semana (domingo)

                $horaLembrete = Carbon::createFromFormat('H:i:s', $lembrete->hora);

                $diaAtual = $hoje->dayOfWeek;

                $diasParaNotificacao = ($diaDaSemana - $diaAtual + 7) % 7;

                $dataNotificacao = $semanaAtual->copy()->addDays($diasParaNotificacao)->setTime($horaLembrete->hour, $horaLembrete->minute);

                if ($dataNotificacao->isPast()) {
                    $dataNotificacao->addWeek();
                }

                foreach ($destinatarios as $destinatario) {

                    $usuario = User::select('id')
                        ->where('email', $destinatario)
                        ->first();

                    Notificacao::create([
                        'data_disparo' => $dataNotificacao,
                        'confirmado'   => 0,
                        'id_lembrete'  => $lembrete->id,
                        'id_usuario'   => $usuario->id,
                    ]);
                }
            }

        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
