<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function contrato($pasta, $file)
    {

        if (! file_exists(env('APP_PATH')."storage/app/contrato/".$pasta."/".$file)) {

            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('contrato/'.$pasta.'/'.$file);
    }

    public function declaration($file)
    {
        if (! file_exists(env('APP_PATH')."storage/app/declaration/".$file)) {
            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('declaration/'.$file);
    }
}
