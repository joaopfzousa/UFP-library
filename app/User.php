<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function allowedReservations()
    {
        return 3;
    }

    public function allowedReservationDays()
    {
        switch($this->degree) {
            case 0:
                $days = 10;
                break;
            case 1:
                $days = 5;
                break;
            case 2:
                $days = 30;
                break;
            case 3:
                $days = 5;
                break;
            case 4:
                $days = 5;
                break;
        }

        return $days;
    }

    public function numberOfActiveReservations()
    {
        return $this->reservations()->where('status', '=', '0')->count();
    }

    public function getFineInformation($bookReservationDate)
    {
        $returnDate = Carbon::now();
        $bookReservationDate = Carbon::parse($bookReservationDate);

        if ($returnDate->diffInDays($bookReservationDate) > $this->allowedReservationDays()) {
            return ($returnDate->diffInDays($bookReservationDate) - $this->allowedReservationDays()) * 0.5;
        }

        return 0;
    }

    public function getAllReservations()
    {
        return $this->reservations()
        ->join('books', 'books.id', '=', 'reservations.book_id')
        ->select('reservations.*', 'books.title AS book_title')
        ->get();
    }

    public function getActiveReservations()
    {
        return $this->reservations()
        ->join('books', 'books.id', '=', 'reservations.book_id')
        ->select('reservations.*', 'books.title AS book_title')
        ->where('status', '0')
        ->get();
    }

    private function reservations()
    {
        return $this->hasMany('App\BookReservation');
    }
}
