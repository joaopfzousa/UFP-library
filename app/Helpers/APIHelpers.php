<?php

namespace App\Helpers;

class APIHelpers
{
    public function jsonResponse($entity)
    {
        return $entity->toJson();
    }

    public function validateRequest($controller, $request, $rules)
    {
        $controller->validate($request, $rules);
    }
}