<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumbs extends Component
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = $this->generateBreadcrumbs();
    }

    public function render()
    {
        return view('components.breadcrumbs');
    }

    protected function generateBreadcrumbs()
    {
        $breadcrumbs = [];
        $segments = request()->segments();

        foreach ($segments as $key => $segment) {
            $url = implode('/', array_slice($segments, 0, $key + 1));
            $breadcrumbs[] = [
                'url' => url($url),
                'label' => ucwords(str_replace(['-', '_'], ' ', $segment)),
                'active' => ($key === count($segments) - 1),
            ];
        }

        return $breadcrumbs;
    }
}