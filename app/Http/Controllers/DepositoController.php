<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposito;
use Barryvdh\DomPDF\Facade\Pdf;

class DepositoController extends Controller
{
    public function index()
    {
        // Ambil data deposito dari database (sesuaikan dengan model Filament kamu)
        $depositos = Deposito::where('is_active', true)
            ->orderBy('tenor', 'asc')
            ->get();

        return view('deposito.index', compact('depositos'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1000000',
            'tenor' => 'required|integer|min:1|max:24',
            'suku_bunga' => 'required|numeric|min:0|max:100'
        ]);

        $nominal = $request->nominal;
        $tenor = $request->tenor;
        $sukuBunga = $request->suku_bunga / 100;
        $pajak = 0.20; // Pajak 20%

        // Hitung bunga per bulan
        $bungaKotor = ($nominal * $sukuBunga * $tenor) / 12;
        $pajakBunga = $bungaKotor * $pajak;
        $bungaBersih = $bungaKotor - $pajakBunga;
        $totalPencairan = $nominal + $bungaBersih;

        return response()->json([
            'nominal' => number_format($nominal, 0, ',', '.'),
            'tenor' => $tenor,
            'suku_bunga' => $request->suku_bunga,
            'bunga_kotor' => number_format($bungaKotor, 0, ',', '.'),
            'pajak' => number_format($pajakBunga, 0, ',', '.'),
            'bunga_bersih' => number_format($bungaBersih, 0, ',', '.'),
            'total_pencairan' => number_format($totalPencairan, 0, ',', '.')
        ]);
    }

    public function downloadBrosur()
    {
        $depositos = Deposito::where('is_active', true)
            ->orderBy('tenor', 'asc')
            ->get();

        $pdf = Pdf::loadView('deposito.brosur', compact('depositos'));
        return $pdf->download('brosur-deposito-bjb.pdf');
    }
}