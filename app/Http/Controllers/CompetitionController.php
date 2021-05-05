<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgeRatingResource;
use App\Http\Resources\CompetitionResource;
use App\Http\Resources\RaceResource;
use App\Http\Resources\TypeOfRaceResource;
use App\Models\Competition;
use App\Models\Race;
use App\Models\Runner;
use App\Models\TypeOfRace;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\Console\Input\Input;

class CompetitionController extends Controller
{
     /**
     * Inclusão de provas
     *
     * @param Request $request A Request HTTP
     * @return CompetitionResource A corrida/prova criada encapsulada em um JsonResource
     */
    public function create(Request $request)
    {
        $sdata = $request->getContent();
        $odata = json_decode($sdata);
        $error_messages = [];

        foreach (['runners_id', 'races_id'] as $campo) {
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

        $runner = Runner::find($odata->runners_id);
        if (empty($runner)) {
            return response()->json([
                'code' => 404,
                'message' => 'Corredor não encontrado'
            ], 404);
        }

        $race = Race::find($odata->races_id);
        if (empty($race)) {
            return response()->json([
                'code' => 404,
                'message' => 'Prova não encontrada'
            ], 404);
        }

        $runner_races = $runner->competitions()->with('races')->get();

        $hasRunnerForDate = $runner_races->where('races.race_date', '=', $race->race_date);

        if (count($hasRunnerForDate)) {
            return response()->json([
                'code' => 400,
                'message' => 'O corredor já está inscrito em uma corrida no mesmo dia.'
            ], 404);
        }


        try {
            $competition = new Competition();
            $competition->runners()->associate($runner);
            $competition->races()->associate($race);
            $competition->save();
            $competition->refresh();

            return new CompetitionResource($competition);
        } catch (\Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => "Erro ao tentar inserir o corredor a prova.",
                'description' => $ex->getMessage()
            ], 500);
        }
    }


    /**
     * Inclusão de resultados dos corredores
     *
     * @param Request $request A Request HTTP
     * @return CompetitionResource O resultado de um corredor em uma prova encapsulada em um JsonResource
     */
    public function associeateresult(Request $request)
    {
        $sdata = $request->getContent();
        $odata = json_decode($sdata);
        $error_messages = [];

        foreach (['runners_id', 'races_id', 'race_start_time', 'race_end_time'] as $campo) {
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


        $competition = Competition::where('runners_id', '=', $odata->runners_id)
                                ->where('races_id', '=', $odata->races_id)
                                ->first();

        if (!$competition) {
            return response()->json([
                'code' => 404,
                'message' => 'Prova e/ou Corredor não encontrado'
            ], 404);
        }

        try {
            $competition->race_start_time = $odata->race_start_time;
            $competition->race_end_time = $odata->race_end_time;
            $competition->save();
            $competition->refresh();

            return new CompetitionResource($competition);
        } catch (\Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => "Erro ao tentar inserir o resultado de um corredor a prova.",
                'description' => $ex->getMessage()
            ], 500);
        }

        return new CompetitionResource($competition);
    }

    /**
     * Listagem de classificação das provas gerais
     *
     * @return TypeOfRaceResource A classificação geral por Tipo de Corrida encapsulada em um JsonResource
     */
    public function getOverallRating()
    {

        $type_of_races = TypeOfRace::with('races', 'races.competitions')->get();

        return new TypeOfRaceResource($type_of_races);
    }

    /**
     * Listagem de classificação das provas por idade
     *
     * @return void
     */
    public function getAgeRating()
    {

        $types_of_races = TypeOfRace::all();
        $result = collect([]);

        foreach ($types_of_races as $type) {
            $races = $type->races()->get();
            $custom_types_of_race = collect($type);
            $custom_races = collect([]);


            foreach ($races as $race) {
                $race_collection = collect($race);
                $competition = $race->competitions()->with('runners')->get();

                $ageBetween18and25 = $competition->whereBetween('runners.age', [18, 25]);
                $ageBetween25and35 = $competition->whereBetween('runners.age', [25, 35]);
                $ageBetween35and45 = $competition->whereBetween('runners.age', [35, 45]);
                $ageBetween45and55 = $competition->whereBetween('runners.age', [45, 55]);
                $ageBetween55and99 = $competition->whereBetween('runners.age', [55, 99]);

                $custom_competitions = collect([]);
                $custom_competitions->put('18-25', $ageBetween18and25->sortBy('position_by_age')->values());
                $custom_competitions->put('25-35', $ageBetween25and35->sortBy('position_by_age')->values());
                $custom_competitions->put('35-45', $ageBetween35and45->sortBy('position_by_age')->values());
                $custom_competitions->put('45-55', $ageBetween45and55->sortBy('position_by_age')->values());
                $custom_competitions->put('55-99', $ageBetween55and99->sortBy('position_by_age')->values());


                $race_collection->put('competitions', $custom_competitions);
                $custom_races->push($race_collection);
            }

            $custom_types_of_race->put('races', $custom_races);

            $result->push($custom_types_of_race);
        }

        return new AgeRatingResource($result);
    }
}
