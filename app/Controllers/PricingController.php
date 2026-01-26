<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Service;

class PricingController
{
    public function index()
    {
        $services = Service::all();
        return View::render('pricing', [
            'services' => $services
        ]);
    }
}
