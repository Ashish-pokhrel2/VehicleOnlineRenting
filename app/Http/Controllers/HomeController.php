<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredVehicles = Vehicles::with('vendor:id,name')
            ->where('available', true)
            ->orderByDesc('rating')
            ->limit(3)
            ->get();

        $typeCounts = Vehicles::query()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => Str::plural($row->type->value ?? (string) $row->type),
                'count' => $row->total,
            ]);

        return view('welcome', [
            'featuredVehicles' => $featuredVehicles,
            'typeCounts' => $typeCounts,
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }
}
