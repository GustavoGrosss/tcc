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

        $query = User::select('usuarios.*')
            ->join('titulares_secundarios', 'titulares_secundarios.id_titular', 'usuarios.id')
            ->where('titulares_secundarios.id_titular', 3)
            ->orderBy('usuarios.id', 'desc')
            ->toSql();

        dd($query);

        $this->alert('Script Finalizado - ' . now());
    }
}
