<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Data berdasarkan role
        if ($user->role === 'departemen') {
            // Hanya surat yang dibuat oleh user ini
            $surats = Surat::where('created_by', $user->id)
                ->latest()
                ->take(5)
                ->get();

            // Surat keluar yang masih bisa direvisi/diedit
            $pendingCount = Surat::where('created_by', $user->id)
                ->where('jenis', 'keluar')
                ->whereIn('approval_status', ['pending_wr', 'rejected_wr'])
                ->count();
        } elseif ($user->role === 'wakil_rektor') {
            // Hanya surat keluar yang menunggu persetujuan WR
            $surats = Surat::where('jenis', 'keluar')
                ->where('approval_status', 'pending_wr')
                ->latest()
                ->take(5)
                ->get();

            $pendingCount = Surat::where('jenis', 'keluar')
                ->where('approval_status', 'pending_wr')
                ->count();
        } elseif ($user->role === 'rektor') {
            // Surat keluar yang sudah disetujui WR → siap ditandatangani
            $surats = Surat::where('jenis', 'keluar')
                ->where('approval_status', 'approved_wr')
                ->latest()
                ->take(5)
                ->get();

            $pendingCount = Surat::where('jenis', 'keluar')
                ->where('approval_status', 'approved_wr')
                ->count();
        } else { // admin
            $surats = Surat::latest()->take(5)->get();
            // Total surat yang belum selesai (belum returned/archived)
            $pendingCount = Surat::whereNotIn('approval_status', ['archived', 'returned'])
                ->count();
        }

        $totalSurat = Surat::count();
        $totalUser = User::count();

        return view('dashboard', compact('surats', 'pendingCount', 'totalSurat', 'totalUser'));
    }
}