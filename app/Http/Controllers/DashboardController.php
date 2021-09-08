<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getIndex() {
        $data = [];
        $data['page_title'] = "OKOK";
        return view("dashboard", $data);
    }
}
