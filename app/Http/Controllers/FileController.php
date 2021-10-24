<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public static function uploadDataFile(Request $request)
    {
        $requestInputFieldName = 'data_file';
        if (!$request->hasFile($requestInputFieldName)) {
            $status = 'danger';
            $statusMessage = 'File not found! Please try again.';
        } elseif (!$request->file($requestInputFieldName)->isValid()) {
            $status = 'danger';
            $statusMessage = 'File is not valid! Please try again.';
        } else {
            $file = $request->file($requestInputFieldName);

            $status = 'success';
            $statusMessage = 'Data from uploaded file has been processed!';
        }

        return response()->json([
            'status' => $status,
            'message' => $statusMessage,
        ]);
    }
}
