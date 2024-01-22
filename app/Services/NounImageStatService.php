<?php

namespace App\Services;

class NounImageStatService {
   public function process(string $decodedSVG): stdClass
   {
      // Define local vars
      $xml = simplexml_load_string($decodedSVG);
      $allRectangles = [];
      $area = 0;
      $localArea = 0;
      $localIntersectionArea = 0;
      $index = 0;
      $histogram = [];

      // Process SVG
      foreach($xml->rect as $rectangle) 
      {
         // We want to ignore background rectangle
         if($rectangle['width'] == "100%" || $rectangle['height'] == "100%")
            continue;

         // Define rectangle A bounds
         $leftA = $rectangle['x'];
         $rightA = $rectangle['x'] + $rectangle['width'];
         $topA = $rectangle['y'];
         $bottomA = $rectangle['y'] + $rectangle['height'];

         // Get area of rectangle A
         $localArea = $rectangle['width'] * $rectangle['height'];

         // Get intersection area
         foreach($allRectangles as $processedRectangle)
         {       
            //array_reverse($allRectangles)

            // Define rectangle B bounds
            $leftB = $processedRectangle->x;
            $rightB = $processedRectangle->x + $processedRectangle->width;
            $topB = $processedRectangle->y;
            $bottomB = $processedRectangle->y + $processedRectangle->height;

            // Calculate the overlap
            $xOverlap = max(0, min($rightA, $rightB) - max($leftA, $leftB));
            $yOverlap = max(0, min($bottomA, $bottomB) - max($topA, $topB));

            // Calculate the total intersection of the rectangles
            $localIntersectionArea = ($xOverlap * $yOverlap);
         }

         // Add to list for further intersection checks
         $procesedRectangle = new stdClass();
         $procesedRectangle->x = $rectangle['x'];
         $procesedRectangle->y = $rectangle['y'];
         $procesedRectangle->width = $rectangle['width'];
         $procesedRectangle->height = $rectangle['height'];
         $procesedRectangle->color = (string) $rectangle['fill'];
         $allRectangles[$index] = ($procesedRectangle);

         // Calculate total area to add
         $totalAreaToAdd = max(0, $localArea - $localIntersectionArea);

         // Add the count to the histogram
         $histogram[$procesedRectangle->color] = (isset($histogram[$procesedRectangle->color]) ? $histogram[$procesedRectangle->color] : 0) + $totalAreaToAdd;

         // Add the count to the total area
         $area += $totalAreaToAdd;

         $index++;
      }

      // Aggregate data into object and return it
      $data = new stdClass();
      $data->area = $area;
      $data->histogram = $histogram;
      $data->totalRectangles = $index;

      return $data;
   }

   public function weight(array $histogram): int
   {
      // Get weight
      $weight = 0; 
      $keys = array_keys($histogram);

      foreach ($keys as $key) 
      {
         // Remove #
         $hexColor = substr($key, 1);

         // Turn byte to RBG integers
         $splitHexColor = str_split($hexColor, 2 );

         // Average them to turn to greyscale
         $greyScaleValue = (hexdec($splitHexColor[0]) + hexdec( $splitHexColor[1]) + hexdec($splitHexColor[2])) / 3;

         // Calculate weight and add to aggregate
         $weight += $greyScaleValue * $histogram[$key];
      }

      return $weight;
   }
}