<?php
declare(strict_types=1);

namespace App\Libs;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class CloudVision
{
    public function labelDetection($image)
    {
        $vision = new ImageAnnotatorClient();
        $response = $vision->webDetection($image);
        $web = $response->getWebDetection();

        return $web->getBestGuessLabels();
    }
}
