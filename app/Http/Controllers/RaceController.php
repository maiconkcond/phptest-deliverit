<?php

namespace App\Http\Controllers;

use App\Http\Resources\RaceResource;
use App\Models\Race;
use App\Models\TypeOfRace;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    /**
     * Inclusão de provas
     *
     * @param Request $request A Request HTTP
     * @return RaceResource A corrida/prova criada encapsulada em um JsonResource
     */
    public function create(Request $request)
    {
        $sdata = $request->getContent();
        $odata = json_decode($sdata);
        $error_messages = [];

        foreach (['type_of_races_id', 'race_date'] as $campo) {
            if (!property_exists($odata, $campo) || $odata->$campo == null || $odata->$campo == '') {
                array_push($error_messages, "Campo $campo é obrigatório");
            }
        }

        if (!empty($error_messages)) {
            return response()->json([
                'code' => 400,
                'message' => $error_messages
            ], 404);
        }

        $type_of_race = TypeOfRace::find($odata->type_of_races_id);

        if (empty($type_of_race)) {
            return response()->json([
                'code' => 404,
                'message' => 'Tipo de Prova não encontrado'
            ], 404);
        }

        try {
            $race = new Race();
            $race->typeOfRace()->associate($type_of_race);
            $race->race_date = $odata->race_date;
            $race->save();
            $race->refresh();

            return new RaceResource($race);
        } catch (\Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => "Erro ao tentar inserir uma Prova.",
                'description' => $ex->getMessage()
            ], 500);
        }
    }
}
