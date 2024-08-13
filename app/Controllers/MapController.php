<?php

namespace App\Controllers;

class MapController extends BaseController
{
    public function index()
    {
        $markers = $this->processData();
        return view('map_view', ['markers' => json_encode($markers)]);
    }

    private function processData()
    {
        $markers = [
            [
                'name' => 'Labersa, Balige',
                'lat' => 2.3386506562408704,
                'lon' => 99.0819425911695,
                'type' => 'hotel',
                'url' => 'https://labersatoba.com'
            ],
            // Add more markers here...
        ];

        // Add more locations from the PDF...
        // Example:
        // ['name' => 'Rose\'s Homestay', 'lat' => 2.3456, 'lon' => 99.1234, 'type' => 'homestay'],
        // ['name' => 'RM. Sinar Minang', 'lat' => 2.3789, 'lon' => 99.2345, 'type' => 'restaurant'],
        // ['name' => 'Museum TB. SIlalahi Center', 'lat' => 2.4012, 'lon' => 99.3456, 'type' => 'attraction'],

        return $markers;
    }
}