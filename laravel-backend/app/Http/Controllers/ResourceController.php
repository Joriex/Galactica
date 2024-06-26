<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResourcesResource;
use App\Models\Bases;

class ResourceController extends Controller
{
    public function fetch ($base_id)
    {
        $base = $this->checkBaseAndUser($base_id);
        if(!$base instanceof Bases){
            return $base;
        }

        $resources = $base->resources;
        if(!$resources){
            return response()->json(self::getApiErrorMessage('Resources Information missing.', 200));
        }
        return new ResourcesResource($resources);
    }
}
