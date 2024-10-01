<?php

namespace App\Jobs;

use App\Models\Lembretes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessarNotificacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lembreteId;

    public function __construct($lembreteId)
    {
        $this->lembreteId = $lembreteId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lembrete = Lembretes::with('dia_semana', 'destinatarios')->find($this->lembreteId);

        if ($lembrete && $lembrete->dia_semana->isNotEmpty() && $lembrete->destinatarios->isNotEmpty()) {
            Lembretes::processarNotificacao($lembrete);
        }
    }
}
