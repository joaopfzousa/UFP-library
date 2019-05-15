<?php

namespace App\Helpers;

class APIHelpers
{
    public function jsonResponse($user)
    {
        return $user->toJson();
    }

    public function validateRequest($request, $rules)
    {
        $this->validate($request, $rules);
    }
}