<?php

namespace App\Http\Controllers;

use App\Http\Resources\RunnerResource;
use App\Models\Runner;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RunnerController extends Controller
{
    /**
     * Inclusão de corredores para uma corrida
     *
     * @param Request $request A Request HTTP
     * @return RunnerResource O corredor criado encapsulado em um JsonResource
     */
    public function create(Request $request)
    {
        $sdata = $request->getContent();
        $odata = json_decode($sdata);
        $error_messages = [];

        foreach (['name', 'cpf', 'birthdate'] as $campo) {
            if (!property_exists($odata, $campo) || $odata->$campo == null || $odata->$campo == '') {
                array_push($error_messages, "Campo $campo é obrigatório");
            }
        }

        if (!empty($error_messages)) {
            return response()->json([
                'code' => 400,
                'message' => $error_messages
            ], 400);
        }

        $age = Carbon::parse($odata->birthdate)->age;

        if ($age < 18) {
            return response()->json([
                'code' => 400,
                'message' => 'Não é permitido a inclusão de menores de idade'
            ], 400);
        }


        try {
            $runner = new Runner();
            $runner->name =  $odata->name;
            $runner->cpf = $odata->cpf;
            $runner->birthdate = $odata->birthdate;
            $runner->save();
            $runner->refresh();

            return new RunnerResource($runner);
        } catch (\Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => "Erro ao tentar inserir o Corredor.",
                'description' => $ex->getMessage()
            ], 500);
        }
    }
}
