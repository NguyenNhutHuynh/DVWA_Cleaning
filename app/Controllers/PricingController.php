<?php

namespace App\Controllers;

use App\Core\View;

class PricingController
{
    public function index()
    {
        return View::render('pricing');
    }
}
