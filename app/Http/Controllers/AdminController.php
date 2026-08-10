<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Evaluation;
use App\Models\Pdf;
use App\Models\PurchaseOrder;
use App\Models\Requests;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')
            ->when($request->role && $request->role !== 'all', function ($q) use ($request) {
                $q->where('role', $request->role);
            })
            ->when($request->status && $request->status !== 'all', function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('activity', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%")
                        ->orWhere('ip_address', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($user) use ($request) {
                            $user->where('name', 'like', "%{$request->search}%")
                                ->orWhere('email', 'like', "%{$request->search}%");
                        });
                });
            })
            ->latest();

        $logs = $query->paginate(15);

        return response()->json($logs);
    }

    public function exportActivityLogs(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->role && $request->role !== 'all', function ($q) use ($request) {
                $q->where('role', $request->role);
            })
            ->when($request->status && $request->status !== 'all', function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('activity', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%")
                        ->orWhere('ip_address', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($user) use ($request) {
                            $user->where('name', 'like', "%{$request->search}%")
                                ->orWhere('email', 'like', "%{$request->search}%");
                        });
                });
            })
            ->latest()
            ->get();

        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Role', 'Activity', 'Description', 'Status', 'IP Address', 'User Agent', 'Date & Time']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->user->email ?? 'N/A',
                    strtoupper($log->role),
                    $log->activity,
                    $log->description,
                    strtoupper($log->status),
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                    $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }





public function dashboard(Request $request)
{
    $users = User::whereIn('role', ['administrator', 'pgso'])->get();

    $pos = PurchaseOrder::latest()->get();


    $endUsers = PurchaseOrder::select('end_user')
        ->whereNotNull('end_user')
        ->distinct()
        ->orderBy('end_user')
        ->pluck('end_user');

    $suppliers = PurchaseOrder::select('supplier')
        ->whereNotNull('supplier')
        ->distinct()
        ->orderBy('supplier')
        ->pluck('supplier');

    $requestMessages = Requests::with(['evaluation', 'user'])
        ->latest()
        ->get()
        ->map(function ($request) {
            return (object)[
                'type'          => 'request',
                'evaluation_id' => $request->evaluation_id ?? ($request->evaluation->id ?? null),
                'po_no'         => $request->evaluation->po_no ?? 'No PO Number',
                'status'        => $request->status,
                'created_at'    => $request->created_at,
                'user'          => $request->user->name ?? 'Unknown User',
            ];
        });

    $pdfMessages = Pdf::with('user')
        ->where('status', 'approved')
        ->latest()
        ->get()
        ->map(function ($pdf) {
            return (object)[
                'type' => 'pdf',
                'po_no' => 'PDF Document',
                'status' => 'approved',
                'created_at' => $pdf->created_at,
                'user' => $pdf->user->name ?? 'Unknown User',
            ];
        });

    $messages = collect()
        ->merge($requestMessages)
        ->merge($pdfMessages)
        ->sortByDesc('created_at')
        ->values()
        ->take(10);

    $approvedCount = Pdf::where('status', 'approved')->count();

    return view('admin.dashboard', compact(
        'users',
        'pos',
        'messages',
        'approvedCount',
        'endUsers',
        'suppliers'
    ));
}

public function fetchData(Request $request)
{
    $user = $request->user();

    $query = Evaluation::notDeleted()
        ->with(['criteriaScores', 'digitalApprovals', 'office'])
        ->where('status', 'submitted');

    // ROLE FILTER
    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }

    // 🔎 SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('po_no', 'like', "%{$search}%")
              ->orWhere('supplier_name', 'like', "%{$search}%")
              ->orWhereHas('office', function ($qo) use ($search) {
                  $qo->where('name', 'like', "%{$search}%");
              });
        });
    }

    //  PERIOD YEAR FILTER
    if ($request->filled('period_year')) {
        $query->where('period_year', $request->period_year);
    }

    // FETCH DATA
    $evaluations = $query->latest()->get()->map(function ($evaluation) {

        $percentageMap = [
            1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
            2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
            3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
            4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        ];

        $totalScore = 0;

        foreach ($evaluation->criteriaScores as $score) {
            $criteriaId = $score->criteria_id;
            $rating = $score->number_rating;

            if (isset($percentageMap[$criteriaId][$rating])) {
                $totalScore += $percentageMap[$criteriaId][$rating];
            }
        }

        return [
            'id' => $evaluation->id,
            'po_no' => $evaluation->po_no,
            'supplier_name' => $evaluation->supplier_name,
            'office_name' => optional($evaluation->office)->name,

            // SCORE
            'total_score' => $totalScore ? round($totalScore, 2) : '-',

            // DATE DISPLAY
            'date_evaluation' => $evaluation->date_evaluation
                ? Carbon::parse($evaluation->date_evaluation)->format('Y-m-d')
                : '-',

            // COVERED PERIOD
            'covered_period' => $evaluation->covered_period,

            'period_year' => $evaluation->period_year,
        ];
    });

    $years = Evaluation::notDeleted()
        ->select('period_year')
        ->distinct()
        ->orderBy('period_year', 'desc')
        ->pluck('period_year');

    return response()->json([
        'data' => $evaluations,
        'years' => $years,
    ]);
}

public function downloadSummary(Request $request)
{
    // Convert comma-separated string to array
    $ids = $request->input('ids', []);

    if (is_string($ids)) {
        $ids = explode(',', $ids);
    }

    // Keep only numeric IDs
    $ids = array_filter($ids, fn ($id) => is_numeric($id));

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No valid evaluations selected.');
    }

    $evaluations = Evaluation::notDeleted()
        ->with(['criteriaScores.criteria', 'office'])
        ->whereIn('id', $ids)
        ->get();

    if ($evaluations->isEmpty()) {
        return redirect()->back()->with('error', 'No evaluations found.');
    }

    // Rating weights
    $criteriaWeightMap = [
        1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
        2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
        3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
    ];

    // Compute score of every evaluation
    $summary = $evaluations->map(function ($evaluation) use ($criteriaWeightMap) {

        $overallScore = 0;

        foreach ($evaluation->criteriaScores as $score) {
            $criteriaId = $score->criteria_id;
            $rating = $score->number_rating ?? 0;

            $overallScore += $criteriaWeightMap[$criteriaId][$rating] ?? 0;
        }

        return [
            'department' => $evaluation->office->name ?? '',
            'supplier' => $evaluation->supplier_name,
            'score' => $overallScore,
            'remarks' => $evaluation->criteriaScores
                ->filter(fn ($item) => !empty($item->remarks))
                ->map(function ($item) {
                    return [
                        'criteria' => $item->criteria->criteria_name ?? '',
                        'remarks' => $item->remarks,
                    ];
                }),
        ];
    })

    // Group by Department + Supplier
    ->groupBy(function ($item) {
        return $item['department'] . '|' . $item['supplier'];
    })

    ->map(function ($group) {

        $remarks = collect();

        foreach ($group as $item) {
            $remarks = $remarks->merge($item['remarks']);
        }

        return [
            'department' => $group->first()['department'],
            'supplier' => $group->first()['supplier'],
            'total_evaluations' => $group->count(),
            'average_score' => round($group->avg('score'), 2),
            'remarks' => $remarks,
        ];

    })->values();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'pdf.summary',
        compact('summary')
    )->setPaper('a4', 'landscape');

    return $pdf->download('evaluation-summary.pdf');
}




public function getDepartments()
{
    $departments = Evaluation::notDeleted()
        ->with('office')
        ->where('status', 'submitted')
        ->get()
        ->pluck('office.name')
        ->unique()
        ->values();

    return response()->json($departments);
}



public function getDepartmentSuppliers(Request $request, $department)
{
    $year = $request->query('year');

    $query = Evaluation::notDeleted()
        ->where('status', 'submitted')
        ->with('criteriaScores');

    if (!empty($department) && $department !== 'all') {
        $query->whereHas('office', function ($q) use ($department) {
            $q->where('name', $department);
        });
    }

    if (!empty($year) && $year !== 'all') {
        $query->whereYear('date_evaluation', $year);
    }

    $evaluations = $query->get();

    $criteriaWeightMap = [
        1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
        2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
        3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
    ];

    // Group by supplier
    $grouped = $evaluations->groupBy('supplier_name');

    $result = [];

    foreach ($grouped as $supplier => $evals) {

        $totalScore = 0;
        $count = $evals->count();

        foreach ($evals as $evaluation) {

            $evaluationScore = 0;

            foreach ($evaluation->criteriaScores as $score) {
                $criteriaId = $score->criteria_id;
                $rating = $score->number_rating;

                if (isset($criteriaWeightMap[$criteriaId][$rating])) {
                    $evaluationScore += $criteriaWeightMap[$criteriaId][$rating];
                }
            }

            $totalScore += $evaluationScore;
        }

        $average = $count > 0 ? $totalScore / $count : 0;

        $result[] = [
            'supplier' => $supplier,
            'average' => round($average, 2),
            'evaluations_count' => $count
        ];
    }

    // Sort highest to lowest
    usort($result, function ($a, $b) {
        return $b['average'] <=> $a['average'];
    });

    return response()->json($result);
}


public function getMonthlyEvaluations(Request $request, $department)
{
    $year = $request->query('year');

    $query = Evaluation::notDeleted()
        ->select(
            DB::raw('MONTH(date_evaluation) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'submitted');

    if (!empty($department) && $department !== 'all') {
        $query->whereHas('office', function ($q) use ($department) {
            $q->where('name', $department);
        });
    }

    if (!empty($year) && $year !== 'all') {
        $query->whereYear('date_evaluation', $year);
    }

    $data = $query->groupBy(DB::raw('MONTH(date_evaluation)'))
        ->orderBy('month')
        ->get();

    return response()->json($data);
}


public function getSemesterEvaluations(Request $request, $department = 'all')
{
    $year = $request->query('year');

    $query = Evaluation::notDeleted()
        ->where('status', 'submitted')
        ->with('criteriaScores');

    if (!empty($department) && $department !== 'all') {
        $query->whereHas('office', function ($q) use ($department) {
            $q->where('name', $department);
        });
    }

    if (!empty($year) && $year !== 'all') {
        $query->whereYear('date_evaluation', $year);
    }

    $evaluations = $query->get();

    $criteriaWeightMap = [
        1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
        2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
        3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
    ];

    // Group by supplier
    $grouped = $evaluations->groupBy('supplier_name');

    $result = [];

    foreach ($grouped as $supplier => $evals) {
        $sem1Scores = [];
        $sem2Scores = [];

        foreach ($evals as $evaluation) {
            $evaluationScore = 0;

            foreach ($evaluation->criteriaScores as $score) {
                $criteriaId = $score->criteria_id;
                $rating = $score->number_rating;

                if (isset($criteriaWeightMap[$criteriaId][$rating])) {
                    $evaluationScore += $criteriaWeightMap[$criteriaId][$rating];
                }
            }

            $month = $evaluation->date_evaluation ? Carbon::parse($evaluation->date_evaluation)->month : null;

            if ($month) {
                if ($month >= 1 && $month <= 6) {
                    $sem1Scores[] = $evaluationScore;
                } elseif ($month >= 7 && $month <= 12) {
                    $sem2Scores[] = $evaluationScore;
                }
            }
        }

        $sem1Avg = count($sem1Scores) > 0 ? round(array_sum($sem1Scores) / count($sem1Scores), 2) : null;
        $sem2Avg = count($sem2Scores) > 0 ? round(array_sum($sem2Scores) / count($sem2Scores), 2) : null;

        $allScores = array_merge($sem1Scores, $sem2Scores);
        $overallAvg = count($allScores) > 0 ? round(array_sum($allScores) / count($allScores), 2) : null;

        $result[] = [
            'supplier' => $supplier,
            'sem1_avg' => $sem1Avg,
            'sem1_count' => count($sem1Scores),
            'sem2_avg' => $sem2Avg,
            'sem2_count' => count($sem2Scores),
            'overall_avg' => $overallAvg,
            'evaluations_count' => count($allScores),
        ];
    }

    // Sort by overall_avg descending
    usort($result, function ($a, $b) {
        return ($b['overall_avg'] ?? 0) <=> ($a['overall_avg'] ?? 0);
    });

    return response()->json($result);
}


public function downloadSemesterSummary(Request $request)
{
    $department = $request->query('department', 'all');
    $year = $request->query('year', 'all');

    $query = Evaluation::notDeleted()
        ->where('status', 'submitted')
        ->with('criteriaScores');

    if (!empty($department) && $department !== 'all') {
        $query->whereHas('office', function ($q) use ($department) {
            $q->where('name', $department);
        });
    }

    if (!empty($year) && $year !== 'all') {
        $query->whereYear('date_evaluation', $year);
    }

    $evaluations = $query->get();

    $criteriaWeightMap = [
        1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
        2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
        3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
    ];

    $grouped = $evaluations->groupBy('supplier_name');
    $data = [];

    foreach ($grouped as $supplier => $evals) {
        $sem1Scores = [];
        $sem2Scores = [];

        foreach ($evals as $evaluation) {
            $evaluationScore = 0;

            foreach ($evaluation->criteriaScores as $score) {
                $criteriaId = $score->criteria_id;
                $rating = $score->number_rating;

                if (isset($criteriaWeightMap[$criteriaId][$rating])) {
                    $evaluationScore += $criteriaWeightMap[$criteriaId][$rating];
                }
            }

            $month = $evaluation->date_evaluation ? Carbon::parse($evaluation->date_evaluation)->month : null;

            if ($month) {
                if ($month >= 1 && $month <= 6) {
                    $sem1Scores[] = $evaluationScore;
                } elseif ($month >= 7 && $month <= 12) {
                    $sem2Scores[] = $evaluationScore;
                }
            }
        }

        $sem1Avg = count($sem1Scores) > 0 ? round(array_sum($sem1Scores) / count($sem1Scores), 2) : null;
        $sem2Avg = count($sem2Scores) > 0 ? round(array_sum($sem2Scores) / count($sem2Scores), 2) : null;

        $allScores = array_merge($sem1Scores, $sem2Scores);
        $overallAvg = count($allScores) > 0 ? round(array_sum($allScores) / count($allScores), 2) : null;

        $data[] = [
            'supplier' => $supplier,
            'sem1_avg' => $sem1Avg,
            'sem1_count' => count($sem1Scores),
            'sem2_avg' => $sem2Avg,
            'sem2_count' => count($sem2Scores),
            'overall_avg' => $overallAvg,
            'evaluations_count' => count($allScores),
        ];
    }

    usort($data, function ($a, $b) {
        return ($b['overall_avg'] ?? 0) <=> ($a['overall_avg'] ?? 0);
    });

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'pdf.semester_summary',
        compact('data', 'department', 'year')
    )->setPaper('a4', 'landscape');

    return $pdf->download('supplier-semester-evaluations-summary.pdf');
}

public function getMissingPdfPOs(Request $request)
{
    $query = PurchaseOrder::where(function ($q) {
        $q->whereNull('pdf_po')->orWhere('pdf_po', '');
    });

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('po_no', 'like', "%{$search}%")
              ->orWhere('pr_no', 'like', "%{$search}%")
              ->orWhere('item', 'like', "%{$search}%")
              ->orWhere('end_user', 'like', "%{$search}%")
              ->orWhere('supplier', 'like', "%{$search}%");
        });
    }

    if ($request->filled('department') && $request->department !== 'all') {
        $query->where('end_user', $request->department);
    }

    if ($request->filled('supplier') && $request->supplier !== 'all') {
        $query->where('supplier', $request->supplier);
    }

    $pos = $query->latest()->get();

    return response()->json([
        'status' => 'success',
        'data'   => $pos,
        'count'  => $pos->count(),
    ]);
}

public function downloadMissingPdfPOsReport(Request $request)
{
    @ini_set('memory_limit', '1024M');
    @set_time_limit(300);

    $department = $request->query('department', 'all');
    $supplier   = $request->query('supplier', 'all');
    $search     = $request->query('search', '');

    $query = PurchaseOrder::where(function ($q) {
        $q->whereNull('pdf_po')->orWhere('pdf_po', '');
    });

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('po_no', 'like', "%{$search}%")
              ->orWhere('pr_no', 'like', "%{$search}%")
              ->orWhere('item', 'like', "%{$search}%")
              ->orWhere('end_user', 'like', "%{$search}%")
              ->orWhere('supplier', 'like', "%{$search}%");
        });
    }

    if (!empty($department) && $department !== 'all') {
        $query->where('end_user', $department);
    }

    if (!empty($supplier) && $supplier !== 'all') {
        $query->where('supplier', $supplier);
    }

    $pos = $query->latest()->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'pdf.missing_pdf_pos',
        compact('pos', 'department', 'supplier')
    )->setPaper('a4', 'landscape');

    return $pdf->download('purchase-orders-pending-pdf-report.pdf');
}



    /**
     * Threat & Security Scanner View
     */
    public function threatScannerView()
    {
        return view('admin.threat-scanner');
    }

    /**
     * Run real-time threat scan across uploaded files
     */
    public function runThreatScan()
    {
        $scanner = new \App\Services\FileSecurityScanner();

        $storagePath = storage_path('app');
        $publicUploads = public_path('uploads');

        $scannedFiles = array_merge(
            $scanner->scanDirectory($storagePath),
            $scanner->scanDirectory($publicUploads)
        );

        $totalFiles = count($scannedFiles);
        $threatCount = 0;
        $cleanCount = 0;

        foreach ($scannedFiles as $file) {
            if ($file['status'] === 'THREAT') {
                $threatCount++;
            } else {
                $cleanCount++;
            }
        }

        return response()->json([
            'status' => 'success',
            'summary' => [
                'total_files'  => $totalFiles,
                'clean_count'  => $cleanCount,
                'threat_count' => $threatCount,
                'system_status'=> $threatCount > 0 ? 'THREAT_DETECTED' : 'SECURE'
            ],
            'files' => $scannedFiles
        ]);
    }

    /**
     * Delete/quarantine threat file
     */
    public function deleteThreatFile(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        $filePath = base_path($request->file_path);

        // Prevent directory traversal attacks
        if (strpos(realpath($filePath), base_path()) !== 0) {
            return response()->json([
                'message' => 'Invalid file path.'
            ], 400);
        }

        if (file_exists($filePath) && is_file($filePath)) {
            @unlink($filePath);

            ActivityLog::create([
                'user_id'     => auth()->id(),
                'role'        => auth()->user()->role,
                'activity'    => 'Security Threat Deleted',
                'description' => "Deleted suspicious/threat file: {$request->file_path}",
            ]);

            return response()->json([
                'message' => 'Threat file successfully deleted and removed from server.'
            ]);
        }

        return response()->json([
            'message' => 'File not found or already deleted.'
        ], 440);
    }

    /**
     * Delete all identified threats in bulk
     */
    public function deleteAllThreats()
    {
        $scanner = new \App\Services\FileSecurityScanner();

        $storagePath = storage_path('app');
        $publicUploads = public_path('uploads');

        $scannedFiles = array_merge(
            $scanner->scanDirectory($storagePath),
            $scanner->scanDirectory($publicUploads)
        );

        $deletedCount = 0;
        foreach ($scannedFiles as $file) {
            if ($file['status'] === 'THREAT') {
                $filePath = base_path($file['path']);
                if (file_exists($filePath) && is_file($filePath)) {
                    @unlink($filePath);
                    $deletedCount++;
                }
            }
        }

        if ($deletedCount > 0) {
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'role'        => auth()->user()->role,
                'activity'    => 'Bulk Threat Removal',
                'description' => "Bulk deleted {$deletedCount} detected threat file(s).",
            ]);

            return response()->json([
                'message' => "Successfully removed {$deletedCount} threat file(s) from server."
            ]);
        }

        return response()->json([
            'message' => 'No active threats found to delete.'
        ]);
    }
}
