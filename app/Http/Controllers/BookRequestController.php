<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\BookReservation;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;

class BookRequestController extends Controller
{
    private $user;
    private $book;
    private $bookReservation;
    private $request;
    private $apiHelpers;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Book $book, BookReservation $bookReservation, Request $request, APIHelpers $apiHelpers)
    {
        $this->user = $user;
        $this->book = $book;
        $this->bookReservation = $bookReservation;
        $this->request = $request;
        $this->apiHelpers = $apiHelpers;
    }

    public function create()
    {
        $this->apiHelpers->validateRequest($this, $this->request, [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'reservation_date' => 'required|date'
        ]);

        $userId = $this->request->input('user_id');
        $bookId = $this->request->input('book_id');
        $reservationDate = $this->request->input('reservation_date');
        $status = $this->request->input('status');
        $renovation = $this->request->input('renovation');

        $user = $this->request->auth;

        if ($user->numberOfActiveReservations() >= $user->allowedReservations()) {
            abort(403);
        }

        $bookReservation = new BookReservation;

        $bookReservation->user_id = intval($userId);
        $bookReservation->book_id = intval($bookId);
        $bookReservation->reservation_date = $reservationDate;
        $bookReservation->status = 0;
        $bookReservation->renovation = 0;

        $bookReservation->save();

        return $this->apiHelpers->jsonResponse($bookReservation);
    }

    public function update($reservationId)
    {
        $bookReservation = $this->bookReservation->where('id', $reservationId)->firstOrFail();

        if ($bookReservation->renovation >= 3) {
            abort(403);
        }

        $bookReservation->renovation = $bookReservation->renovation + 1;

        $bookReservation->save();

        return $this->apiHelpers->jsonResponse($bookReservation);
    }

    public function destroy($reservationId)
    {
        $bookReservation = $this->bookReservation->where('id', $reservationId)->firstOrFail();
        $user = $this->request->auth;

        if ($bookReservation->status == 1) {
            abort(403);
        }

        $bookReservation->status = 1;

        $bookReservation->save();

        $bookReservation->payment = $user->getFineInformation($bookReservation->reservation_date);

        return $this->apiHelpers->jsonResponse($bookReservation);
    }
}
