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
        $this->apiHelpers($this->request, [
            'number' => 'required|unique:users',
            'password' => 'required'
        ]);

        $number = $this->request->input('number');
        $password = Hash::make($this->request->input('password'));
        $admin = empty($this->request->input('admin')) ? '0' : $this->request->input('admin');

        $user = new User;

        $user->number = $number;
        $user->password = $password;
        $user->admin = $admin;

        $user->save();
    }

    public function show($number)
    {
        $user = $this->user->where('number', $number)->get();
        
        return $this->apiHelpers->jsonResponse($user);
    }

    public function update($number)
    {
        $user = $this->user->where('number', $number)->get();

        $admin = empty($this->request->input('admin')) ? '0' : $this->request->input('admin');

        $user->save();
    }

    public function destroy($number)
    {
        $user = $this->user->where('number', $number);

        $user->delete();
    }
}
