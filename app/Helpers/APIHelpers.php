<?php

namespace App\Helpers;

class APIHelpers
{
    public function jsonResponse($user)
    {
        return $user->toJson();
    }

    public function validateRequest($controller, $request, $rules)
    {
        $controller->validate($request, $rules);
    }
}