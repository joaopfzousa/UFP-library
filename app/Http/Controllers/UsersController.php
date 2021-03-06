<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\APIHelpers;

class UsersController extends Controller
{
    private $user;
    private $request;
    private $apiHelpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Request $request, APIHelpers $apiHelpers)
    {
        $this->user = $user;
        $this->request = $request;
        $this->apiHelpers = $apiHelpers;
    }

    public function index()
    {
        return $this->apiHelpers->jsonResponse($this->user::all());
    }

    public function create()
    {
        $this->apiHelpers->validateRequest($this, $this->request, [
            'number' => 'required|unique:users',
            'password' => 'required',
            'degree' => 'required'
        ]);

        $number = $this->request->input('number');
        $password = Hash::make($this->request->input('password'));
        $admin = empty($this->request->input('admin')) ? '0' : $this->request->input('admin');
        $degree = $this->request->input('degree');

        $user = new User;

        $user->number = $number;
        $user->password = $password;
        $user->admin = $admin;
        $user->degree = $degree;

        $user->save();
        
        return $this->apiHelpers->jsonResponse($user);
    }

    public function show($number)
    {
        $user = $this->user->where('number', $number)->firstOrFail();

        if (!$user) {
            abort(404);
        }
        
        return $this->apiHelpers->jsonResponse($user);
    }

    public function showAllReservations($number)
    {
        $user = $this->user->where('number', $number)->firstOrFail();

        if (!$user) {
            abort(404);
        }
        
        return $this->apiHelpers->jsonResponse($user->getAllReservations());
    }

    public function showActiveReservations($number)
    {
        $user = $this->user->where('number', $number)->firstOrFail();

        if (!$user) {
            abort(404);
        }
        
        return $this->apiHelpers->jsonResponse($user->getActiveReservations());
    }

    public function update($number)
    {
        $user = $this->user->where('number', $number)->firstOrFail();

        if (!$user) {
            abort(404);
        }

        $admin = empty($this->request->input('admin')) ? '0' : $this->request->input('admin');

        $user->save();

        return $this->apiHelpers->jsonResponse($user);
    }

    public function destroy($number)
    {
        $user = $this->user->where('number', $number)->firstOrFail();

        $user->delete();
    }
}
