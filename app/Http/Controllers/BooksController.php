<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;

class BooksController extends Controller
{
    private $book;
    private $request;
    private $apiHelpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Book $book, Request $request, APIHelpers $apiHelpers)
    {
        $this->book = $book;
        $this->request = $request;
        $this->apiHelpers = $apiHelpers;
    }

    public function index()
    {
        return $this->apiHelpers->jsonResponse($this->book::all());
    }

    public function create()
    {
        $this->apiHelpers->validateRequest($this, $this->request, [
            'isbn' => 'required|unique:books',
            'title' => 'required',
            'author' => 'required',
            'edition' => 'required',
            'publisher' => 'required',
            'location' => 'required'
        ]);

        $isbn = $this->request->input('isbn');
        $title = $this->request->input('title');
        $author = $this->request->input('author');
        $edition = $this->request->input('edition');
        $publisher = $this->request->input('publisher');
        $location = $this->request->input('location');

        $book = new Book;

        $book->isbn = $isbn;
        $book->title = $title;
        $book->author = $author;
        $book->edition = $edition;
        $book->publisher = $publisher;
        $book->location = $location;

        $book->save();
    }

    public function show($isbn)
    {
        $book = $this->book->where('isbn', $isbn)->firstOrFail();

        if (!$book) {
            abort(404);
        }
        
        return $this->apiHelpers->jsonResponse($book);
    }

    public function update($isbn)
    {
        $this->apiHelpers->validateRequest($this, $this->request, [
            'isbn' => 'required|unique:books',
            'title' => 'required',
            'author' => 'required',
            'edition' => 'required',
            'publisher' => 'required',
            'location' => 'required'
        ]);

        $book = $this->book->where('isbn', $isbn)->firstOrFail();

        $isbn = $this->request->input('isbn');
        $title = $this->request->input('title');
        $author = $this->request->input('author');
        $edition = $this->request->input('edition');
        $publisher = $this->request->input('publisher');
        $location = $this->request->input('location');

        $book->isbn = $isbn;
        $book->title = $title;
        $book->author = $author;
        $book->edition = $edition;
        $book->publisher = $publisher;
        $book->location = $location;

        $book->save();
    }

    public function destroy($isbn)
    {
        $book = $this->book->where('isbn', $isbn);

        $book->delete();
    }
}
