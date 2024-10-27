<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\LembreteController;
use App\Jobs\Core\IntegrarOrdensJob;
use App\Jobs\Utils\SoapUtil;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\Ordem;
use App\Models\OrdemItem;
use App\Models\Produto;
use App\Models\TitularesSecundarios;
use DB;
use Illuminate\Console\Command;

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

        $secundarios = TitularesSecundarios::select('usuarios.id', 'usuarios.name')
            ->join('usuarios', 'usuarios.id', 'titulares_secundarios.id_secundario')
            ->where('titulares_secundarios.id_titular', 4)
            ->get();

//        $lembretes = (new LembreteController)->processarNotificacoes((new LembreteController)->lembretesSemana());

        dd($secundarios);

        $this->alert('Script Finalizado - ' . now());
    }
}
