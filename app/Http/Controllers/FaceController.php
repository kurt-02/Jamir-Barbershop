<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class FaceController extends Controller
{
    public function index()
    {
        return view('face-upload'); 
    }

    public function analyze(Request $request)
    {
        // Rate limiting - 5 attempts per hour per IP
        $key = 'face-analysis:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            return back()->withErrors([
                'error' => "Too many attempts. Try again in {$minutes} minute(s)."
            ]);
        }

        RateLimiter::hit($key, 3600);

        // Validate image
        $request->validate([
            'image' => 'required|image'
        ]);

        $imagePath = $request->file('image')->getPathName();

        // 🔥 FACE++ API REQUEST
        $response = Http::asMultipart()->post(
            config('services.facepp.endpoint'),
            [
                [
                    'name' => 'api_key',
                    'contents' => config('services.facepp.key')
                ],
                [
                    'name' => 'api_secret',
                    'contents' => config('services.facepp.secret')
                ],
                [
                    'name' => 'image_file',
                    'contents' => fopen($imagePath, 'r')
                ],
                [
                    'name' => 'return_landmark',
                    'contents' => '1'
                ]
            ]
        );

        if ($response->failed()) {
            return back()->withErrors([
                'error' => 'Face++ API error: ' . $response->body()
            ]);
        }

        $data = $response->json();

        if (empty($data['faces'])) {
            return back()->withErrors([
                'error' => 'No face detected in the image.'
            ]);
        }

        $landmarks = $data['faces'][0]['landmark'];

        // ✅ FACE MEASUREMENTS
        $pupilLeftX  = $landmarks['left_eye_pupil']['x'];
        $pupilRightX = $landmarks['right_eye_pupil']['x'];

        $noseY = $landmarks['nose_tip']['y'];
        $lipY  = $landmarks['mouth_upper_lip_bottom']['y'];

        $pupilDistance = abs($pupilLeftX - $pupilRightX);
        $faceWidth     = $pupilDistance * 2;
        $faceHeight    = abs($noseY - $lipY) * 3;
        $faceRatio     = round($faceHeight / $faceWidth, 2);

        $jawDefinition     = $faceRatio > 1.4 ? 'Sharper jawline' : 'Softer jawline';
        $faceWidthCategory = $faceWidth > 200 ? 'Wide' : 'Narrow';

        // ✅ FACE SHAPE
        $faceShape = $this->determineFaceShape($landmarks);

        // ✅ HAIRCUTS
        $recommendedHaircuts = $this->getHaircutRecommendations($faceShape);

        $recommendations = "Based on your {$faceShape} face shape, these haircuts will enhance balance and proportions.";

        return view('face-result', compact(
            'faceShape',
            'recommendedHaircuts',
            'faceRatio',
            'jawDefinition',
            'faceWidthCategory',
            'recommendations',
            'data'
        ));
    }

    private function determineFaceShape(array $landmarks): string
{
    // ── 1. Guard: require every landmark we'll use ──────────────────────────
    $required = [
        'contour_left5',      // outermost left jaw point (cheekbone level)
        'contour_right5',     // outermost right jaw point (cheekbone level)
        'contour_left9',      // lower jaw, left side
        'contour_right9',     // lower jaw, right side
        'contour_chin',       // lowest point of chin
        'left_eyebrow_left_corner',
        'right_eyebrow_right_corner',
        'left_eye_left_corner',
        'right_eye_right_corner',
    ];

    foreach ($required as $key) {
        if (!isset($landmarks[$key]['x'], $landmarks[$key]['y'])) {
            return 'Unknown';
        }
    }

    // ── 2. Core measurements ────────────────────────────────────────────────

    // Cheekbone width: widest horizontal span of the face
    $cheekW = abs(
        $landmarks['contour_left5']['x'] - $landmarks['contour_right5']['x']
    );

    // Jaw width: lower contour points (roughly at mouth level)
    $jawW = abs(
        $landmarks['contour_left9']['x'] - $landmarks['contour_right9']['x']
    );

    // Forehead width: distance between outer eyebrow corners
    $foreheadW = abs(
        $landmarks['left_eye_left_corner']['x']
        - $landmarks['right_eye_right_corner']['x']
    ) * 1.4;

    // Face height: top of eyebrows to chin
    $browTopY = min(
        $landmarks['left_eyebrow_left_corner']['y'],
        $landmarks['right_eyebrow_right_corner']['y']
    );
    $faceH = abs($landmarks['contour_chin']['y'] - $browTopY);

    // Guard against division by zero
    if ($cheekW < 1 || $faceH < 1) {
        return 'Unknown';
    }

    // ── 3. Normalised ratios ────────────────────────────────────────────────

    $heightRatio    = $faceH / $cheekW;           // > 1.5 = long face
    $jawToForehead  = $jawW   / max($foreheadW, 1);
    $foreToJaw      = $foreheadW / max($jawW, 1);
    $jawToCheek     = $jawW   / max($cheekW, 1);

        // ── 4. Shape classification (fixed & balanced) ───────
    
        // 1. Oblong (long face)
        if ($heightRatio > 1.6) {
            return 'Oblong';
        }
        
        // 2. Square (strong jaw, equal proportions)
        if (
            $heightRatio >= 1.0 && $heightRatio <= 1.3 &&
            abs($jawW - $cheekW) / $cheekW < 0.1 &&
            abs($foreheadW - $jawW) / $jawW < 0.1
        ) {
            return 'Square';
        }
        
        // 3. Round (wide cheeks, soft jaw)
        if (
            $heightRatio < 1.2 &&
            $jawToCheek < 0.85 &&
            $foreToJaw > 0.9 && $foreToJaw < 1.1
        ) {
            return 'Round';
        }
        
        // 4. Triangle (jaw wider than forehead)
        if ($jawToForehead > 1.15) {
            return 'Triangle';
        }
        
        // 5. Diamond (narrow forehead & jaw, wide cheekbones)
        if (
            $foreToJaw < 0.9 &&
            $jawToCheek < 0.8 &&
            $heightRatio > 1.3
        ) {
            return 'Diamond';
        }
        
        // 6. Heart (NOW stricter)
        if (
            $foreToJaw > 1.35 &&
            $jawToCheek < 0.70 &&
            $heightRatio >= 1.2 && $heightRatio <= 1.5
        ) {
            return 'Heart';
        }
        
        // 7. Default
        return 'Round';
    }

    private function getHaircutRecommendations($shape)
    {
        return [
            'Square'   => ['Crew Cut', 'Buzz Cut', 'Mid Fade'],
            'Round'   => ['High Fade', 'Pompadour', 'Spiky Hair'],
            'Oblong'   => ['Fringe', 'Side Part', 'Textured Crop'],
            'Triangle' => ['Volume on top', 'Side Part', 'Quiff'],
            'Diamond'  => ['Fringe', 'Messy Top', 'Low Fade'],
            'Heart'    => ['Medium Length', 'Curtains', 'Textured Top']
        ][$shape] ?? [];
    }
}