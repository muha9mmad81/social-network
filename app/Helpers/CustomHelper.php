<?php

use Illuminate\Support\Facades\File;

function paginate($data)
{
    $collection = collect($data['meta']);
    $data = $collection->put('data', $data['data']);
    return $data;
}

function saveFile(Illuminate\Http\UploadedFile $file, String $subFolder, String $disk = 'local')
{
    $extension = $file->getClientOriginalExtension();
    $fileName = time() . rand(1000, 9999) . '.' . $extension;
    $fileSize = $file->getSize();
    $fileType = $file->getMimeType();

    $sanitizedName = str_replace($extension, '', Illuminate\Support\Str::slug($file->getClientOriginalName()));

    if ($disk == "s3") {
        $path = $subFolder . '/' . $fileName . '.' . $extension;
        \Illuminate\Support\Facades\Storage::disk('s3')->put($path, file_get_contents($file));
    } else {
        if (!File::exists(public_path($subFolder))) {
            File::makeDirectory(public_path($subFolder), 777, true, true);
        }
        $file->move(public_path($subFolder), $fileName);
        $path = "{$subFolder}/{$fileName}";
    }

    return [
        "name" => $fileName,
        "extension" => $extension,
        "fileSize" => $fileSize,
        "fileType" => $fileType,
        "path" => $path,
        "disk" => $disk
    ];
}

function generateUniqueCode($code, $model)
{
    $codeExist = $model::where('token', $code)->first();
    if ($codeExist) {
        $newCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);;
        generateUniqueCode($newCode, $model);
    } else {
        return $code;
    }
}

function generateUniqueRememberToken($code, $model)
{
    $codeExist = $model::where('remember_token', $code)->first();
    if ($codeExist) {
        $newCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);;
        generateUniqueCode($newCode, $model);
    } else {
        return $code;
    }
}
