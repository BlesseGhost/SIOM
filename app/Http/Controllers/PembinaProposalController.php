<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class PembinaProposalController extends Controller
{
    // ✅ List proposal yang masuk ke pembina
    public function index()
    {
        $proposals = Proposal::where('status', 'pending_pembina')->get();
        return view('pages.pembina.index', compact('proposals'));
    }

    // ✅ Fungsi ACC oleh Pembina
    public function acc($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Path file Word asli proposal
        $fileProposalPath = public_path('storage/' . basename($proposal->file_proposal));

        // Load file Word dengan TemplateProcessor
        $templateProcessor = new TemplateProcessor($fileProposalPath);

        // Masukkan tanda tangan pembina
        $templateProcessor->setImageValue('tanda_tangan_pembina', [
            'path' => storage_path('app/public/tanda_tangan/pembina.png'), // Pastikan file ttd ada
            'width' => 150,
            'height' => 100,
            'ratio' => true
        ]);

        // Simpan file hasil (replace file lama)
        $templateProcessor->saveAs($fileProposalPath); // Replace langsung

        // Update status ke kemahasiswaan
        $kemahasiswaanId = 11; // Sesuaikan ID user kemahasiswaan
        $proposal->update([
            'status' => 'pending_kemahasiswaan',
            'current_reviewer_id' => $kemahasiswaanId,
            'ttd_pembina' => 'tanda_tangan/pembina.png'
        ]);

        return back()->with('success', 'Proposal berhasil ACC dan diteruskan ke Kemahasiswaan.');
    }

    // ✅ Fungsi tampilkan form revisi
    public function formRevisi($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('pages.pembina.revisi', compact('proposal'));
    }

    // ✅ Fungsi Proses Revisi
    public function revisi(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);

        $request->validate([
            'file_revisi' => 'required|mimes:doc,docx,pdf|max:2048',
            'catatan_revisi' => 'required|string'
        ]);

        // Simpan file revisi
        $file = $request->file('file_revisi');
        $fileName = 'Revisi_Pembina_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName);

        // Kembalikan ke Admin
        $adminId = 1; // ID Admin

        $proposal->update([
            'file_revisi' => 'storage/' . $fileName,
            'catatan_revisi' => $request->catatan_revisi,
            'status' => 'revisi_pembina',
            'current_reviewer_id' => $adminId,
        ]);

        return redirect()->route('pembina.proposal.index')->with('success', 'Proposal dikembalikan ke Admin untuk revisi.');
    }
}
