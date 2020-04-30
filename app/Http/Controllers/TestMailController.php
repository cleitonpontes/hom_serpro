<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function send( $mail)
    {
        dump($mail);
        Mail::to($mail)->send(new TestMail);
        dd('foi');
    }
}
