<?php

use App\Models\City;
use Illuminate\Support\Facades\Storage;

if (! function_exists('uploadFile')) {
    function uploadFile($file, $path = 'uploads', $oldFile = null)
    {
        if (! $file) {
            return $oldFile;
        }
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        return $file->store($path, 'public');
    }
}
if (! function_exists('deleteFile')) {
    function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}

if (! function_exists('getFileUrl')) {
    function getFileUrl($filePath)
    {
        if ($filePath) {
            return asset('storage/' . $filePath);
        }

        return asset('assets/images/avatar.png');
    }
}

if (! function_exists('amountInWords')) {

    function amountInWords($amount)
    {
        $formatter = new NumberFormatter(
            'en_IN',
            NumberFormatter::SPELLOUT
        );

        return ucwords($formatter->format($amount));
    }
}

function getCitiesByState($stateId)
{
    return City::where('state_id', $stateId)
        ->orderBy('city')
        ->get();
}