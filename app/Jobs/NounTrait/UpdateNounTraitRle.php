<?php

namespace App\Jobs\NounTrait;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NounTrait;
use Illuminate\Support\Facades\Storage;
use Exception;
use SimpleXMLElement;

class UpdateNounTraitRle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nounTrait;

    /**
     * Create a new job instance.
     */
    public function __construct(NounTrait $nounTrait)
    {
        $this->nounTrait = $nounTrait;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if SVG exists
        if (!Storage::exists($this->nounTrait->svg_path)) {
            throw new Exception("SVG file does not exist at path: {$this->nounTrait->svg_path}");
        }

        try {
            $svgContent = Storage::get($this->nounTrait->svg_path);
            $rleData = $this->encodeSvgToRle($svgContent);

            $this->nounTrait->update(['rle_data' => $rleData]);
        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }

    /**
     * Encode SVG content to RLE format.
     *
     * @param string $svgContent
     * @return string
     */
    protected function encodeSvgToRle(string $svgContent): string
    {
        $rleData = '';
        
        $xml = new SimpleXMLElement($svgContent);

        foreach ($xml->children() as $element) {
            $rleData .= $this->encodeElement($element);
        }

        return $rleData;
    }

    /**
     * Encode an individual SVG element to RLE.
     *
     * @param SimpleXMLElement $element
     * @return string
     */
    protected function encodeElement(SimpleXMLElement $element): string
    {
        $rleString = '';

        switch ($element->getName()) {
            case 'rect':
                $width = isset($element['width']) ? (int)$element['width'] : 0;
                $height = isset($element['height']) ? (int)$element['height'] : 0;
                $x = isset($element['x']) ? (int)$element['x'] : 0;
                $y = isset($element['y']) ? (int)$element['y'] : 0;
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';
                $rleString .= "RECT|X{$x}|Y{$y}|W{$width}|H{$height}|C{$color}|";
                break;

            case 'circle':
                $radius = isset($element['r']) ? (int)$element['r'] : 0;
                $cx = isset($element['cx']) ? (int)$element['cx'] : 0;
                $cy = isset($element['cy']) ? (int)$element['cy'] : 0;
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';
                $rleString .= "CIRCLE|CX{$cx}|CY{$cy}|R{$radius}|C{$color}|";
                break;

            case 'ellipse':
                $rx = isset($element['rx']) ? (int)$element['rx'] : 0;
                $ry = isset($element['ry']) ? (int)$element['ry'] : 0;
                $cx = isset($element['cx']) ? (int)$element['cx'] : 0;
                $cy = isset($element['cy']) ? (int)$element['cy'] : 0;
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';
                $rleString .= "ELLIPSE|CX{$cx}|CY{$cy}|RX{$rx}|RY{$ry}|C{$color}|";
                break;

            case 'line':
                $x1 = isset($element['x1']) ? (int)$element['x1'] : 0;
                $y1 = isset($element['y1']) ? (int)$element['y1'] : 0;
                $x2 = isset($element['x2']) ? (int)$element['x2'] : 0;
                $y2 = isset($element['y2']) ? (int)$element['y2'] : 0;
                $color = isset($element['stroke']) ? (string)$element['stroke'] : '#000000';
                $strokeWidth = isset($element['stroke-width']) ? (int)$element['stroke-width'] : 1;
                $rleString .= "LINE|X1{$x1}|Y1{$y1}|X2{$x2}|Y2{$y2}|C{$color}|SW{$strokeWidth}|";
                break;

            case 'polygon':
                $points = isset($element['points']) ? (string)$element['points'] : '';
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';
                $rleString .= "POLYGON|P{$points}|C{$color}|";
                break;

            case 'polyline':
                $points = isset($element['points']) ? (string)$element['points'] : '';
                $color = isset($element['stroke']) ? (string)$element['stroke'] : '#000000';
                $strokeWidth = isset($element['stroke-width']) ? (int)$element['stroke-width'] : 1;
                $rleString .= "POLYLINE|P{$points}|C{$color}|SW{$strokeWidth}|";
                break;

            case 'path':
                $d = isset($element['d']) ? (string)$element['d'] : '';
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';
                $rleString .= "PATH|D{$d}|C{$color}|";
                break;

            default:
                \Log::info("Unsupported SVG element type: " . $element->getName());
                break;
        }

        return $rleString;
    }
}
