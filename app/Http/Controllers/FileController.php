<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Upload file for processing
     * Ref: https://laravel.com/docs/8.x/requests#files
     * Ref: https://github.com/symfony/symfony/blob/5.4/src/Symfony/Component/HttpFoundation/File/UploadedFile.php
     */
    public static function uploadDataFile(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestInputFieldName = 'data_file';
        if (!$request->hasFile($requestInputFieldName)) {
            $status = 'danger';
            $statusMessage = 'File not found! Please try again.';
        } elseif (!$request->file($requestInputFieldName)->isValid()) {
            $status = 'danger';
            $statusMessage = 'File is not valid! Please try again.';
        } else {
            $uploadedFile = $request->file($requestInputFieldName);
            $results = static::processDataFile($uploadedFile);

            $status = 'success';
            $statusMessage = 'Data from uploaded file has been processed!';
        }

        return response()->json([
            'status' => $status,
            'message' => $statusMessage,
            'results' => $results ?? null,
        ]);
    }

    /**
     * Process census file, retrieve 3rd column (7_2009)
     */
    public static function processDataFile(\Illuminate\Http\UploadedFile $uploadedFile)
    {
        $fileDirectory = 'uploads';
        $fileName = 'tmp-file';
        $uploadedFile->move($fileDirectory, $fileName);
        $filePath = public_path($fileDirectory . '/' . $fileName);

        $file = fopen($filePath, "r");
        $results = [];
        while (($row = fgetcsv($file, 1000, "\t")) !== false) {
            // Todo: Ignore 1st row
            $results[] = substr($row[2], 0, 1);     // Get 1st digit only
        }

        array_shift($results);      // Remove first element which is the label 7_2009
        return $results;
    }
}
