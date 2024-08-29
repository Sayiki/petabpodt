<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LocationController extends BaseController
{
    public function importLocations()
    {
        $locationModel = new LocationModel();

        // Parse the HTML content and extract location data
        $htmlContent = file_get_contents('path/to/your/html/file.html');
        $dom = new \DOMDocument();
        @$dom->loadHTML($htmlContent);
        $script = $dom->getElementsByTagName('script')[1]->nodeValue;

        preg_match_all('/(\w+):\s*\[(.*?)\]/s', $script, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $category = $match[1];
            $locations = explode('},', $match[2]);

            foreach ($locations as $location) {
                preg_match('/lat:\s*([\d.]+),\s*lng:\s*([\d.]+),\s*title:\s*"([^"]+)"/', $location, $locationData);

                if (count($locationData) === 4) {
                    $locationModel->insert([
                        'category' => $category,
                        'title' => $locationData[3],
                        'latitude' => $locationData[1],
                        'longitude' => $locationData[2]
                    ]);
                }
            }
        }

        return 'Data imported successfully';
    }
}