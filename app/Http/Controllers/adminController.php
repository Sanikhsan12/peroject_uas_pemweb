<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\buku;
use App\Models\pinjam;
use App\Models\pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class adminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getAllBooks()
    {
        $books = buku::with('kategori')->get();
        return response()->json($books);
    }

    public function getAllBorrows()
    {
        $borrows = pinjam::with(['buku', 'user'])->orderBy('tanggal_pinjam', 'desc')->get();
        return response()->json($borrows);
    }

    public function getAllReturns()
    {
        $returns = pengembalian::with(['pinjam.buku', 'pinjam.user'])->orderBy('tanggal_kembali', 'desc')->get();
        return response()->json($returns);
    }
    
    public function history()
    {
        $returned = pengembalian::with(['pinjam.buku','pinjam.user'])->orderBy('tanggal_kembali','desc')->get();
        $borrowed = pinjam::with(['buku','user'])->whereNotIn('id', $returned->pluck('pinjam_id'))->orderBy('tanggal_pinjam','desc')->get();

        return response()->json([
            'returned' => $returned,
            'borrowed' => $borrowed
        ]);
    }

    public function markAsReturned($id)
    {
        try {
            $loan = pinjam::findOrFail($id);
            
            // Cek apakah sudah dikembalikan
            if ($loan->status === 'dikembalikan') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Buku ini sudah dikembalikan'
                ], 400);
            }
            
            // Update book quantity
            $book = buku::findOrFail($loan->buku_id);
            $book->increment('jumlah');
            
            // Calculate late days and fine
            $borrowDate = Carbon::parse($loan->tanggal_pinjam);
            $dueDate = $borrowDate->copy()->addDays(7); // Deadline 7 hari dari peminjaman
            $returnDate = Carbon::now();
            
            // Hitung denda jika melewati deadline
            $fine = 0;
            if ($returnDate->isAfter($dueDate)) {
                $lateDays = $returnDate->diffInDays($dueDate);
                $fine = $lateDays * 3000; // Rp 3.000 per hari terlambat
            }
            
            // Update loan status
            $loan->update(['status' => 'dikembalikan']);
            
            // Create return record
            pengembalian::create([
                'pinjam_id' => $id,
                'tanggal_kembali' => $returnDate,
                'denda' => $fine
            ]);

            $message = 'Buku berhasil dikembalikan';
            if ($fine > 0) {
                $message .= ". Terlambat " . $lateDays . " hari, dikenakan denda Rp " . number_format($fine, 0, ',', '.');
            } else {
                $message .= " tepat waktu";
            }

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengembalikan buku'
            ], 500);
        }
    }

    public function exportBooksPDF()
    {
        $books = buku::with('kategori')->get();

        $html = '<h1>Daftar Buku</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;border-collapse:collapse;">';
        $html .= '<thead>';
        $html .= '<tr><th>ID</th><th>Kategori</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>ISBN</th><th>Tahun</th><th>Jumlah</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($books as $book) {
            $html .= '<tr>';
            $html .= '<td>' . $book->id . '</td>';
            $html .= '<td>' . $book->kategori->nama ?? 'N/A' . '</td>';
            $html .= '<td>' . $book->judul . '</td>';
            $html .= '<td>' . $book->penulis . '</td>';
            $html .= '<td>' . $book->penerbit . '</td>';
            $html .= '<td>' . $book->isbn . '</td>';
            $html .= '<td>' . $book->tahun . '</td>';
            $html .= '<td>' . $book->jumlah . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $pdf = PDF::loadHTML($html);
        return $pdf->download('daftar-buku.pdf');
    }

    public function exportBorrowsPDF()
    {
        $borrows = pinjam::with(['buku', 'user'])->get();

        $html = '<h1>Daftar Peminjaman</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;border-collapse:collapse;">';
        $html .= '<thead>';
        $html .= '<tr><th>ID</th><th>Peminjam</th><th>Buku</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($borrows as $borrow) {
            $html .= '<tr>';
            $html .= '<td>' . $borrow->id . '</td>';
            $html .= '<td>' . $borrow->user->name . '</td>';
            $html .= '<td>' . $borrow->buku->judul . '</td>';
            $html .= '<td>' . $borrow->tanggal_pinjam . '</td>';
            $html .= '<td>' . $borrow->tanggal_kembali . '</td>';
            $html .= '<td>' . $borrow->status . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $pdf = PDF::loadHTML($html);
        return $pdf->download('daftar-peminjaman.pdf');
    }

    public function exportReturnsPDF()
    {
        $returns = pengembalian::with(['pinjam.buku', 'pinjam.user'])->get();

        $html = '<h1>Daftar Pengembalian</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;border-collapse:collapse;">';
        $html .= '<thead>';
        $html .= '<tr><th>ID</th><th>Peminjam</th><th>Buku</th><th>Tanggal Kembali</th><th>Denda</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($returns as $return) {
            $html .= '<tr>';
            $html .= '<td>' . $return->id . '</td>';
            $html .= '<td>' . $return->pinjam->user->name . '</td>';
            $html .= '<td>' . $return->pinjam->buku->judul . '</td>';
            $html .= '<td>' . $return->tanggal_kembali . '</td>';
            $html .= '<td>Rp ' . number_format($return->denda, 0, ',', '.') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $pdf = PDF::loadHTML($html);
        return $pdf->download('daftar-pengembalian.pdf');
    }

    public function store(Request $request)
    {
        try {
            $book = buku::create($request->all());
            return response()->json(['success' => true, 'data' => $book]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = buku::findOrFail($id);
            $book->update($request->all());
            return response()->json(['success' => true, 'data' => $book]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $book = buku::findOrFail($id);
            $book->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
