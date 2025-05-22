<?php

namespace App\Http\Controllers;

use App\Services\BookStackCalculationService;
use App\Models\BookStackCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookStackCalculationController extends Controller
{
    protected BookStackCalculationService $calculationService;

    public function __construct(BookStackCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Determine sorting direction, default to 'desc' (newest first)
        $sort = $request->input('sort', 'desc');

        // Validate input to allow only 'asc' or 'desc'
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        // Query calculations ordered by created_at
        $calculations = BookStackCalculation::where('user_id', $user->id)
            ->orderBy('created_at', $sort)
            ->paginate(5)
            ->appends(['sort' => $sort]); // Preserve sort query in pagination links

        return Inertia::render('Dashboard', [
            'calculations' => $calculations,
            'sort' => $sort,
            'success' => $request->session()->get('success'),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'length' => 'required|integer|min:1|max:50',
            'matrix' => 'required|array|size:' . $request->length,
            'matrix.*' => 'array|size:' . $request->length,
            'matrix.*.*' => 'integer|min:0|max:1000',
        ]);
        $start = microtime(true);
        $visible = $this->calculationService->calculateVisibleStacks($validated['matrix']);
        $end = microtime(true);
        $calculationTime = ($end - $start) * 1000;
        $user->bookStackCalculations()->create([
            'length' => $validated['length'],
            'matrix' => $validated['matrix'],
            'visible' => $visible,
            'calculation_time' => $calculationTime,
        ]);

        return redirect()
            ->route('dashboard.calculations')
            ->with(['success' => 'Calculation saved successfully.']);
    }

    public function exportCsv(Request $request, $id)
    {
        $calculation = $request->user()->bookStackCalculations()->findOrFail($id);

        $filename = "stacks_{$calculation->id}_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($calculation) {
            $file = fopen('php://output', 'w');

            // Write matrix rows
            foreach ($calculation->matrix as $rowIndex => $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
