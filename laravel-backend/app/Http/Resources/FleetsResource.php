<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expedition = $this->getOngoingExpedition();
        return [
            'id' => $this->id,
            'lightFighter' => $this->light_fighter,
            'transporter' => $this->transporter,
            'heavyFighter' => $this->heavy_fighter,
            'battleship' => $this->battleships,
            'cruiser' => $this->cruiser,
            'name' =>$this->name,
            'idleShips' => $this->harbour->getIdleShips(),
            'busy' => $this->busy,
            'strength' => $this->getBattleStrength(),
            'expedition' => $expedition ? (new ExpeditionResource($expedition))->withoutFleet() : null,
        ];
    }
}
