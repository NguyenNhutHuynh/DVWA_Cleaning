<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Models\Service;

class ServicesController
{
    public function index()
    {
        $services = Service::all();
        
        return View::render('services', [
            'services' => $services
        ]);
    }

    public function show($id)
    {
        $service = Service::getById($id);
        
        if (!$service) {
            return View::render('404');
        }

        return View::render('service-detail', [
            'service' => $service
        ]);
    }
}
