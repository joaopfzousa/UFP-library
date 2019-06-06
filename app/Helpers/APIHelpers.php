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

    public static function getBookCoverImage($isbn)
    {
        $apiInformation = json_decode(self::fetchGoogleBooksAPI($isbn));

        return $apiInformation->items[0]->volumeInfo->imageLinks->thumbnail;
    }

    private static function fetchGoogleBooksAPI($isbn)
    {
        $apiKey = env('GOOGLE_BOOKS_API_KEY');
        $url = "https://www.googleapis.com/books/v1/volumes?key=$apiKey&q=isbn:$isbn";

        $ch = curl_init();
        $headers = [];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch);
    }
}