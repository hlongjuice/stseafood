<?php

namespace App\Http\Controllers\Web\DownloadApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //App Update Link
    public function index()
    {
        return redirect(
            'https://drive.google.com/drive/folders/1H5T4R_YQqf_F5S_yV7h6gVKP0IbEEaU_?usp=sharing');
    }

    public function webUpdateFile(){
        return redirect(
            'https://drive.google.com/drive/folders/1CzYaAILOuhbZJCXKJ5PjA1HPQnzT9Fk8?usp=sharing'
        );
    }


}
