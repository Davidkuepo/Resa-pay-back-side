<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $baseUrl = url('/images');

        $hotels = [
            [
                "id" => 1,
                "name" => "Hôtel Parisien",
                "city" => "Paris",
                "price" => 120,
                "rating" => 4.5,
                "reviews" => 32,
                "image" => "$baseUrl/hotel1.jpg",
                "description" => "Un hôtel moderne situé au cœur de Paris."
            ],
            [
                "id" => 2,
                "name" => "Résidence Belle Vue",
                "city" => "Lyon",
                "price" => 90,
                "rating" => 4.2,
                "reviews" => 18,
                "image" => "$baseUrl/hotel2.jpg",
                "description" => "Confort et élégance pour votre séjour à Lyon."
            ],
            [
                "id" => 3,
                "name" => "Meublé des Alpes",
                "city" => "Chamonix",
                "price" => 150,
                "rating" => 4.8,
                "reviews" => 45,
                "image" => "$baseUrl/hotel3.jpg",
                "description" => "Chalet luxueux avec vue sur les montagnes."
            ]
        ];
    
        return response()->json($hotels);
    }
    

    public function show($id)
    {
        $baseUrl = url('/images');
        $hotels = [
            1 => [
                'id' => 1,
                'name' => 'Hôtel du Soleil',
                'city' => 'Paris',
                'price' => 120,
                'rating' => 4.5,
                'discount' => 10,
                'commentsCount' => 128,
                'features' => ['Wi-Fi', 'Parking', 'Piscine'],
                'images' => [
                    "$baseUrl/hotel1.jpg",
                    "$baseUrl/hotel2.jpg",
                ]
            ],
            2 => [
                'id' => 2,
                'name' => 'Hôtel de la Plage',
                'city' => 'Nice',
                'price' => 90,
                'rating' => 4.2,
                'discount' => 5,
                'commentsCount' => 64,
                'features' => ['Wi-Fi', 'Climatisation'],
                'images' => [
                    "$baseUrl/hotel3.jpg",
                    "$baseUrl/hotel2.jpg",
                ]
            ]
        ];
    
        if (!isset($hotels[$id])) {
            return response()->json(['error' => 'Hôtel non trouvé'], 404);
        }
    
        return response()->json($hotels[$id]);
    }
}
