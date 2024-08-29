<?php

namespace App\Controllers;

use App\Models\LocationModel;

class MapController extends BaseController
{
    public function index()
    {
        $locationModel = new LocationModel();
        $locations = $locationModel->findAll();

        $markers = $this->processData($locations);
        return view('map_view', ['markers' => json_encode($markers)]);
    }

    private function processData($locations)
    {
        $markers = [];
        foreach ($locations as $location) {
            $markers[] = [
                'title' => $location['title'],
                'lat' => (float) $location['latitude'],
                'lng' => (float) $location['longitude'],
                'category' => $location['category'],
                'url' => $location['url']
            ];
        }
        return $markers;
    }
}