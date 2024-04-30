<?php

namespace App\Http\Controllers;

use App\Http\Resources\FleetsResource;
use App\Http\Resources\HarbourResource;
use App\Http\Resources\FleetsCollection;
use App\Models\Bases;
use App\Models\Fleets;
use App\Models\Harbours;
use Illuminate\Http\Request;

class FleetsController extends Controller
{
    public function fetch ($base_id)
    {
        $base = $this->checkBaseAndUser($base_id);
        if(!$base instanceof Bases){
            return $base;
        }

        $fleets = $base->harbour->fleets;
        if(!$fleets){
            return response()->json(self::getApiErrorMessage('Resources Information missing.'));
        }
        return new FleetsCollection($fleets);
    }

    public function create (Request $request)
    {
        $harbour_id = $request->input('harbour_id');
        if(!$harbour_id){
            return response()->json(self::getApiErrorMessage('Harbour id missing!'));
        }
        $name = $request->input('name');
        if(!$name){
            return response()->json(self::getApiErrorMessage('Name for fleet missing!'));
        }
        if(stripos($name, "Pirates") !== false){
            return response()->json(self::getApiErrorMessage('The Name of your fleet cant contain "Pirates"!'));
        }
        $harbour = Harbours::find($harbour_id);
        if(!$harbour){
            return response()->json(self::getApiErrorMessage('No harbour with given id found!'));
        }
        $base = $harbour->base;
        $base = self::checkBaseAndUser($base->id);
        if(!$base instanceof Bases){
            return $base;
        }
        if(sizeof($harbour->fleets) >= $harbour->fleet_cap){
            return response()->json(self::getApiErrorMessage('You exceeded your fleet limit!'));
        }

        $fleet = Fleets::create([
            'name' => $name,
        ]);
        $fleet->harbour()->associate($harbour);
        $fleet->save();
        $harbour->load('fleets');
        return new HarbourResource($harbour);
    }

    public function update(Request $request)
    {
        $fleet_id = $request->input('fleet_id');
        $lf = $request->input('light_fighter');
        $hf = $request->input('heavy_fighter');
        $c = $request->input('cruiser');
        $t = $request->input('transporter');
        $b = $request->input('battleships');

        if(!$fleet_id || is_null($lf) || is_null($hf) || is_null($c) || is_null($t) || is_null($b)){
            return response()->json(self::getApiErrorMessage('Data missing!'));
        }

        if($lf < 0 || $hf < 0 || $c < 0 || $t < 0 || $b < 0){
            return response()->json(self::getApiErrorMessage('Cant set negative amount of Ships!'));
        }

        $fleet = Fleets::find($fleet_id);
        if(!$fleet){
            return response()->json(self::getApiErrorMessage('No fleet with given id found!'));
        }

        $harbour = $fleet->harbour;
        if(!isset($fleet->harbour)){
            return response()->json(self::getApiErrorMessage('Internal error!(maybe attempting to try edit PirateFleets? :/'));
        }
        $base = $fleet->harbour->base;

        $base = self::checkBaseAndUser($base->id);
        if(!$base instanceof Bases){
            return $base;
        }

        $idleShips = $harbour->getIdleShips();
        if($lf > $idleShips['light_fighter'] + $fleet->light_fighter){
            return response()->json(self::getApiErrorMessage('Not enough idle Light Fighters!'));
        }
        if($hf > $idleShips['heavy_fighter'] + $fleet->heavy_fighter){
            return response()->json(self::getApiErrorMessage('Not enough idle Heavy Fighters!'));
        }
        if($c > $idleShips['cruiser'] + $fleet->cruiser){
            return response()->json(self::getApiErrorMessage('Not enough idle Cruiser!'));
        }
        if($b > $idleShips['battleships'] + $fleet->battleships){
            return response()->json(self::getApiErrorMessage('Not enough idle Battleships!'));
        }
        if($t > $idleShips['transporter'] + $fleet->transporter){
            return response()->json(self::getApiErrorMessage('Not enough idle Transporter!'));
        }

        $fleet->update([
            'heavy_fighter' => $hf,
            'light_fighter' => $lf,
            'battleships' => $b,
            'transporter' => $t,
            'cruiser' => $c,
        ]);
        $fleet->save();
        $harbour->load('fleets');

        return new FleetsResource($fleet);
    }
}
