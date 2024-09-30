<?php

namespace App\Console\Commands;

use App\Jobs\Core\IntegrarOrdensJob;
use App\Jobs\Utils\SoapUtil;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\Ordem;
use App\Models\OrdemItem;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;

class teste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script Extract';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Script Inicial - ' . now());

        $antes = Carbon::parse('2024-09-16 14:34:17');
        $dps   = Carbon::parse('2024-09-16 14:34:20');

        dd($dps->greaterThan($antes));

        function calcularLembretesParaAProximaSemana()
        {
            $agora = Carbon::now();

            $lembretes = [];

            for ($diaDaSemana = 0; $diaDaSemana < 7; $diaDaSemana++) {

                $proximaOcorrencia = Carbon::now()->next($diaDaSemana);
//                $proximaOcorrencia = Carbon::parse('2024-09-27 11:10:00')->next($diaDaSemana);

                if ($diaDaSemana === $agora->dayOfWeek && $agora->hour < 24) {
                    $proximaOcorrencia = $agora; // Mantém o horário atual para hoje
                }

                $tempoFaltando = $agora->diffInSeconds($proximaOcorrencia);

                $diaDaSemanaTexto = '';

                switch ($diaDaSemana) {
                    case '0':
                        {
                            $diaDaSemanaTexto = 'DOMINGO';
                        }
                        break;
                    case '1':
                        {
                            $diaDaSemanaTexto = 'SEGUNDA';
                        }
                        break;
                    case '2':
                        {
                            $diaDaSemanaTexto = 'TERÇA';
                        }
                        break;
                    case '3':
                        {
                            $diaDaSemanaTexto = 'QUARTA';
                        }
                        break;
                    case '4':
                        {
                            $diaDaSemanaTexto = 'QUINTA';
                        }
                        break;
                    case '5':
                        {
                            $diaDaSemanaTexto = 'SEXTA';
                        }
                        break;
                    case '6':
                        {
                            $diaDaSemanaTexto = 'SABADO';
                        }
                        break;
                }

                $lembretes[] = [
                    'dia_da_semana'      => $diaDaSemanaTexto,
                    'proxima_ocorrencia' => $proximaOcorrencia->toDateTimeString(),
                    'tempo_faltando'     => $tempoFaltando,
                ];
            }

            return $lembretes;
        }

        $lembretes = calcularLembretesParaAProximaSemana();

        dd($lembretes);

        $this->alert('Script Finalizado - ' . now());
    }
}
