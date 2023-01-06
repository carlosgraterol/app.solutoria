<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pago;
use App\Models\Indicador;
use Http;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        //Importar data desde API
        /*** $response = $this->importarData() ***/
        //Todos los registros
        $indicadores = Indicador::where('codigoIndicador', 'UF')->orderBy('id', 'DESC')->get();
        //Últimos 7 registros
        $grafico = Indicador::select('id', 'valorIndicador', 'fechaIndicador')->where('codigoIndicador', 'UF')
                            ->orderBy('id', 'DESC')
                            ->orderBy('fechaIndicador', 'ASC')
                            ->take(7)->get();       
        return view('home', compact('grafico', 'indicadores'));
    }

    public function store(Request $request)
    {   
        if ($request->ajax()) {
            if($request->id == ''){
                $indicador = Indicador::create([
                    'nombreIndicador'       => $request->nombreIndicador,
                    'codigoIndicador'       => $request->codigoIndicador,
                    'unidadMedidaIndicador' => $request->unidadMedidaIndicador,
                    'valorIndicador'        => $request->valorIndicador,
                    'fechaIndicador'        => $request->fechaIndicador,
                    'tiempoIndicador'       => NULL,
                    'origenIndicador'       => $request->origenIndicador
                ]);
            }else{
                $indicador = Indicador::find($request->id)->update([
                    'nombreIndicador'       => $request->nombreIndicador,
                    'codigoIndicador'       => $request->codigoIndicador,
                    'unidadMedidaIndicador' => $request->unidadMedidaIndicador,
                    'valorIndicador'        => $request->valorIndicador,
                    'fechaIndicador'        => $request->fechaIndicador,
                    'origenIndicador'       => $request->origenIndicador
                ]);
            }

            //Últimos 7 registros
            $grafico = Indicador::select('id', 'valorIndicador', 'fechaIndicador')->where('codigoIndicador', 'UF')
                                ->orderBy('id', 'DESC')
                                ->orderBy('fechaIndicador', 'ASC')
                                ->take(7)->get();            
            return response()->json(['data' => $indicador, 'grafico' => $grafico], 200);
        }else{
            return response()->json(['data' => 'No se pudo realizar la petición'], 500);
        }
    }

    public function filtro(Request $request)
    {   
        if ($request->ajax()) {
            //Últimos 7 registros
            $grafico = Indicador::select('id', 'valorIndicador', 'fechaIndicador')
                                ->where('codigoIndicador', 'UF')
                                ->whereBetween('fechaIndicador', [$request->fechaInicio, $request->fechaFin])
                                ->orderBy('id', 'DESC')
                                ->take(7)->get();  

            return response()->json(['grafico' => $grafico], 200);
        }else{
            return response()->json(['data' => 'No se pudo realizar la petición'], 500);
        }
    }

    public function importarData(){
        $consultaApi = Http::withHeaders([
                            'Authorization' => 'Bearer ' . env('TOKEN_SOLUTORIA'),
                        ])->get('https://postulaciones.solutoria.cl/api/indicadores');

        $response = json_decode($consultaApi);

        //Indicadores BD
        $indicadores = Indicador::where('codigoIndicador', 'UF')->orderBy('id', 'DESC')->get();
        
        //Aplica esta logica para solo importar 1 vez
        if($response && count($indicadores) == 0){
            foreach($response as $res){
                Indicador::create([
                    'nombreIndicador'       => $res->nombreIndicador,
                    'codigoIndicador'       => $res->codigoIndicador,
                    'unidadMedidaIndicador' => $res->unidadMedidaIndicador,
                    'valorIndicador'        => $res->valorIndicador,
                    'fechaIndicador'        => $res->fechaIndicador,
                    'tiempoIndicador'       => $res->tiempoIndicador,
                    'origenIndicador'       => $res->origenIndicador
                ]);
            }

            return true;
        }else{
            return false;
        }
    }

    public function borrar(Request $request)
    {   
        if ($request->ajax()) {
            $indicador = Indicador::where('id', $request->id)->delete();
            if($indicador){
                $grafico = Indicador::select('id', 'valorIndicador', 'fechaIndicador')->where('codigoIndicador', 'UF')
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('fechaIndicador', 'ASC')
                                    ->take(7)->get();
                return response()->json(['status' => 200, 'grafico' => $grafico], 200);
            }else{
                return response()->json(['status' => 500], 500);
            }
        }else{
            return response()->json(['data' => 'No se pudo realizar la petición'], 500);
        }
    }
}
