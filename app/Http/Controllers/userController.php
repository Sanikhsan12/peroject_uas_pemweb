<?php

namespace App\Http\Controllers;
use App\Models\buku;
use App\Models\pengembalian;
use App\Models\pinjam;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = buku::where('judul','LIKE',"%$query%")->orWhere('penulis','LIKE',"%$query%")->orWhere('isbn','LIKE',"%$query%")->with('kategori')->get();

        return response()->json($books);
    }
    
    public function history()
    {
        $user_id = auth()->user()->id;

        $returned = pengembalian::with(['pinjam.buku','pinjam.user'])->whereHas('pinjam', function($query) use ($user_id){
            $query->where('user_id', $user_id);
        })->orderBy('tanggal_kembali','desc')->get();

        $borrowerd = pinjam::with(['buku','user'])->where('user_id', $user_id)->whereNotIn('id', $returned->pluck('pinjam_id'))->orderBy('tanggal_pinjam','desc')->get();

        return response()->json([
            'returned' => $returned,
            'borrowed' => $borrowerd
        ]);
    }

    public function bookDetails($id)
    {
        $book = buku::with('kategori')
            ->findOrFail($id);
            
        // Check if book is currently borrowed
        $isBookBorrowed = pinjam::where('buku_id', $id)
            ->where('status', 'dipinjam')
            ->exists();
            
        // Get current borrower if applicable
        $currentBorrower = null;
        if ($isBookBorrowed) {
            $currentBorrower = pinjam::where('buku_id', $id)
                ->where('status', 'dipinjam')
                ->with('user:id,name')
                ->first()
                ->user;
        }
        
        return response()->json([
            'book' => $book,
            'isBorrowed' => $isBookBorrowed,
            'currentBorrower' => $currentBorrower
        ]);
    }

    public function borrowBook($id)
{
    try {
        $book = buku::findOrFail($id);
        
        // Cek apakah buku masih tersedia
        if ($book->jumlah <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak tersedia'
            ], 400);
        }

        // Cek apakah user sudah meminjam buku ini
        $existingLoan = pinjam::where('user_id', auth()->id())
            ->where('buku_id', $id)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingLoan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah meminjam buku ini'
            ], 400);
        }

        // Buat peminjaman baru
        $loan = pinjam::create([
            'user_id' => auth()->id(),
            'buku_id' => $id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        // Kurangi jumlah buku
        $book->decrement('jumlah');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dipinjam',
            'data' => $loan
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat meminjam buku'
        ], 500);
    }
}
}
