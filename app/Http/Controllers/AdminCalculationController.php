<?php

namespace App\Http\Controllers;

use App\Models\BookStackCalculation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCalculationController extends Controller
{
    public function index(Request $request)
    {
        // Determine sorting direction, default to 'desc' (newest first)
        $sort = $request->input('sort', 'desc');

        // Validate input to allow only 'asc' or 'desc'
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        // Query calculations ordered by created_at
        $calculations = BookStackCalculation::orderBy('created_at', $sort)
            ->with('user')
            ->paginate(5)
            ->appends(['sort' => $sort]); // Preserve sort query in pagination links

        return Inertia::render('DashboardAdmin', [
            'calculations' => $calculations,
            'sort' => $sort,
        ]);
    }
}
