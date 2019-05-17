<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;

class BookRequestController extends Controller
{
    private $user;
    private $book;
    private $request;
    private $apiHelpers;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Book $book, Request $request, APIHelpers $apiHelpers)
    {
        $this->user = $user;
        $this->book = $book;
        $this->request = $request;
        $this->apiHelpers = $apiHelpers;
    }
}
