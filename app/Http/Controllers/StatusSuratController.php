<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\StatusTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya untuk role departemen
        if (Auth::user()->role !== 'departemen') {
            abort(403, 'Unauthorized action.');
        }

        $surats = Surat::where('created_by', Auth::id())
            ->with(['statusTrackings' => function($query) {
                $query->latest();
            }])
            ->latest()
            ->paginate(10);

        return view('status-surat.index', compact('surats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Surat $surat)
    {
        // Authorization check
        if (Auth::user()->role !== 'departemen' || $surat->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $statusHistory = StatusTracking::where('surat_id', $surat->id)
            ->with('user')
            ->latest()
            ->get();

        return view('status-surat.show', compact('surat', 'statusHistory'));
    }
}
