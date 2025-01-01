<?php

namespace App\Http\Controllers;
use App\Models\buku;
use App\Models\pengembalian;
use App\Models\pinjam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

            // Check if book was returned
            $returnedBook = pengembalian::whereHas('pinjam', function($query) use ($id) {
                $query->where('buku_id', $id)
                    ->where('status', 'dipinjam');
            })->first();

            if ($returnedBook) {
                // Update status in pinjam table
                $returnedBook->pinjam->update(['status' => 'dikembalikan']);
                // Increment book quantity
                $book->increment('jumlah');
            }

            // Continue with borrowing logic
            if ($book->jumlah <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Buku tidak tersedia'
                ], 400);
            }

            $loan = pinjam::create([
                'user_id' => auth()->id(),
                'buku_id' => $id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(7),
                'status' => 'dipinjam'
            ]);

            $book->decrement('jumlah');

            return response()->json([
                'status' => 'success',
                'message' => 'Buku berhasil dipinjam'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat meminjam buku'
            ], 500);
        }
    }

    public function exportPDF()
    {
        // Ambil semua data buku dari database dengan relasi kategori
        $buku = Buku::with('kategori')->get();

        // HTML untuk PDF
        $html = '<h1>Daftar Buku</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;border-collapse:collapse;">';
        $html .= '<thead>';
        $html .= '<tr><th>ID</th><th>Kategori</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>ISBN</th><th>Tahun</th><th>Jumlah</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Loop data buku dan masukkan ke dalam tabel HTML
        foreach ($buku as $item) {
            $html .= '<tr>';
            $html .= '<td>' . $item->id . '</td>';
            $html .= '<td>' . $item->kategori->nama ?? 'N/A' . '</td>'; // Menampilkan kategori nama (jika ada)
            $html .= '<td>' . $item->judul . '</td>';
            $html .= '<td>' . $item->penulis . '</td>';
            $html .= '<td>' . $item->penerbit . '</td>';
            $html .= '<td>' . $item->isbn . '</td>';
            $html .= '<td>' . $item->tahun . '</td>';
            $html .= '<td>' . $item->jumlah . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        // Generate PDF dari HTML
        $pdf = PDF::loadHTML($html);

        // Return PDF untuk di-download
        return $pdf->download('buku-list.pdf');
    }
}
