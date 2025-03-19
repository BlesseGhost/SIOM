<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    // ✅ Index halaman form pengajuan proposal
    public function index()
    {
        return view('pages.siswa.materi.index'); // Pastikan sesuai nama file blade form proposal
    }

    // ✅ Generate Proposal & Simpan ke Database
    public function generate(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nomor_urut' => 'required|string',
            'bulan_romawi' => 'required|string',
            'tahun' => 'required|string',
            'ormawa' => 'required|string',
            'dari' => 'required|string',
            'surat_edaran' => 'required|string',
            'nomor_surat_edaran' => 'required|string',
            'kode_anggaran' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'tema_kegiatan' => 'required|string',
            'latar_belakang' => 'required|string',
            'tujuan' => 'required|string',
            'ketua_pelaksana' => 'required|string',
            'nim_ketua' => 'required|string',
            'jumlah_dana' => 'required|numeric',
            'terbilang_dana' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_rapat' => 'required|date',
            'tanggal_surat_edaran' => 'required|date',
            'ttd_ketua' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // Anggaran
            'anggaran' => 'required|array|min:1',
            'anggaran.*.uraian' => 'required|string',
            'anggaran.*.volume' => 'required|numeric',
            'anggaran.*.satuan' => 'required|string',
            'anggaran.*.harga_satuan' => 'required|numeric',
            'anggaran.*.jumlah_total' => 'required|numeric'
        ]);
        // Upload tanda tangan
        $ttdFile = $request->file('ttd_ketua');
        $ttdFileName = 'TTD_Ketua_' . time() . '.' . $ttdFile->getClientOriginalExtension();
        $ttdFile->storeAs('public/ttd', $ttdFileName);
        $ttdPath = storage_path('app/public/ttd/' . $ttdFileName);


        // Path template Word
        $templatePath = public_path('template/format_proposal.docx');
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file tidak ditemukan.');
        }

        // Load template Word
        $templateProcessor = new TemplateProcessor($templatePath);

        $anggaranList = $request->anggaran;
        $rowCount = count($anggaranList);
        $templateProcessor->cloneRow('uraian', $rowCount);


        // Nomor surat lengkap
        $nomorSuratLengkap = 'ND / ' . $request->nomor_urut . ' / ' . $request->bulan_romawi . ' / ' . $request->tahun . ' / ' . $request->ormawa;
        $templateProcessor->setValue('nomor_surat_lengkap', $nomorSuratLengkap);
        $templateProcessor->setImageValue('ttd_ketua', ['path' => $ttdPath, 'width' => 100, 'height' => 50, 'ratio' => true]);


        // Isi placeholder lainnya
        $templateProcessor->setValue('nomor_urut', $request->nomor_urut);
        $templateProcessor->setValue('bulan_romawi', $request->bulan_romawi);
        $templateProcessor->setValue('tahun', $request->tahun);
        $templateProcessor->setValue('ormawa', $request->ormawa);
        $templateProcessor->setValue('dari', $request->dari);
        $templateProcessor->setValue('surat_edaran', $request->surat_edaran);
        $templateProcessor->setValue('nomor_surat_edaran', $request->nomor_surat_edaran);
        $templateProcessor->setValue('tanggal_surat_edaran', date('d F Y', strtotime($request->tanggal_surat_edaran)));
        $templateProcessor->setValue('tanggal_rapat', date('d F Y', strtotime($request->tanggal_rapat)));
        $templateProcessor->setValue('kode_anggaran', $request->kode_anggaran);
        $templateProcessor->setValue('nama_kegiatan', $request->nama_kegiatan);
        $templateProcessor->setValue('tema_kegiatan', $request->tema_kegiatan);
        $templateProcessor->setValue('latar_belakang', $request->latar_belakang);
        $templateProcessor->setValue('tujuan', $request->tujuan);
        $templateProcessor->setValue('ketua_pelaksana', $request->ketua_pelaksana);
        $templateProcessor->setValue('nim_ketua', $request->nim_ketua);
        $templateProcessor->setValue('jumlah_dana', number_format($request->jumlah_dana, 0, ',', '.'));
        $templateProcessor->setValue('terbilang_dana', $request->terbilang_dana);
        $templateProcessor->setValue('tanggal_surat', date('d F Y', strtotime($request->tanggal_surat)));

        foreach ($anggaranList as $index => $item) {
            $i = $index + 1; // Placeholder numbering starts from 1
            $volumeWithSatuan = $item['volume'] . ' ' . $item['satuan'];
            $templateProcessor->setValue("no#{$i}", $i);
            $templateProcessor->setValue("uraian#{$i}", $item['uraian']);
            $templateProcessor->setValue("volume#{$i}", $volumeWithSatuan);
            $templateProcessor->setValue("harga_satuan#{$i}", 'Rp. ' . number_format($item['harga_satuan'], 0, ',', '.'));
            $templateProcessor->setValue("jumlah_total#{$i}", 'Rp. ' . number_format($item['jumlah_total'], 0, ',', '.'));
        }

        // Simpan file Word
        $fileName = 'Proposal_' . time() . '.docx';
        $savePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($savePath);

        // Simpan ke database untuk dikirim ke admin
        $adminId = 1; // Sesuaikan dengan ID admin (bisa query by role)
        Proposal::create([
            'judul_kegiatan' => $request->nama_kegiatan,
            'file_proposal' => 'storage/' . $fileName, // Path relatif
            'dibuat_oleh_user_id' => auth()->id(), // User pengusul
            'status' => 'pending_admin',
            'current_reviewer_id' => $adminId,
            'total_dana' => 9000000, // default total dana
            'sisa_dana' => 9000000   // default sisa dana
        ]);

        // Redirect ke home
        return response()->download($savePath);
    }

    // ✅ Fungsi List Proposal untuk Admin
    public function listProposalAdmin()
    {
        $proposals = Proposal::where('status', '!=', 'acc_final')->get();
        return view('pages.admin.mapel.index', compact('proposals'));
    }

    // ✅ Fungsi ACC Admin
    public function accByAdmin($id)
    {
        $proposal = Proposal::findOrFail($id);
        $pembinaId = 10; // ID Pembina Ormawa, sesuaikan

        $proposal->update([
            'status' => 'pending_pembina',
            'current_reviewer_id' => $pembinaId,
        ]);

        return back()->with('success', 'Proposal diteruskan ke Pembina Ormawa.');
    }

    // ✅ Fungsi Revisi Admin
    public function revisiByAdmin(Request $request, $id)
{
    $proposal = Proposal::findOrFail($id);

    // Validasi file & catatan revisi
    $request->validate([
        'file_revisi' => 'required|mimes:doc,docx,pdf|max:2048', // Batasi jenis dan ukuran file
    ]);

    // Proses Upload File
    if ($request->hasFile('file_revisi')) {
        // Hapus file revisi lama jika ada
        if ($proposal->file_revisi) {
            Storage::delete(str_replace('storage/', 'public/', $proposal->file_revisi));
        }

        // Simpan file baru
        $file = $request->file('file_revisi');
        $fileName = 'Revisi_Proposal_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName); // Simpan di storage/app/public
        $filePath = 'storage/' . $fileName; // Path untuk disimpan ke DB
    } else {
        return back()->with('error', 'File revisi tidak ditemukan.');
    }

    // Update data proposal
    $proposal->update([
        'status' => 'revisi_admin',
        'file_revisi' => $filePath, // Simpan path file revisi
        'catatan_revisi' => $request->input('catatan_revisi'), // Simpan catatan revisi
        'current_reviewer_id' => $proposal->dibuat_oleh_user_id,
    ]);

    return back()->with('success', 'Proposal berhasil dikembalikan ke pengusul beserta file revisi.');
}
    // ✅ Fungsi Download Proposal
    public function download($id)
{
    $proposal = Proposal::findOrFail($id);
    $filePath = public_path($proposal->file_proposal); // Akses file via public_path

    if (file_exists($filePath)) {
        return response()->download($filePath);
    } else {
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
    
}
public function listUserProposal()
{
    $proposals = Proposal::where('dibuat_oleh_user_id', auth()->id())->get();
    return view('pages.siswa.tugas.index', compact('proposals'));
}
public function hapusProposal($id)
{
    $proposal = Proposal::findOrFail($id);

    // ✅ Cek apakah pemilik atau admin
    if (auth()->id() === $proposal->dibuat_oleh_user_id || auth()->user()->roles === 'admin') {

        // Hapus file proposal
        if ($proposal->file_proposal) {
            Storage::delete(str_replace('storage/', 'public/', $proposal->file_proposal));
        }

        // Hapus file revisi
        if ($proposal->file_revisi) {
            Storage::delete(str_replace('storage/', 'public/', $proposal->file_revisi));
        }

        // Hapus data
        $proposal->delete();

        return back()->with('success', 'Proposal berhasil dihapus.');
    } else {
        return back()->with('error', 'Anda tidak memiliki izin untuk menghapus proposal ini.');
    }
}
public function resubmit(Request $request, $id)
{
    $proposal = Proposal::findOrFail($id);

    // Validasi file revisi yang diajukan ulang
    $request->validate([
        'file_perbaikan' => 'required|mimes:doc,docx,pdf|max:5120', // max 5MB
    ]);

    // Upload file perbaikan
    $file = $request->file('file_perbaikan');
    $fileName = 'Perbaikan_Proposal_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('public', $fileName); // storage/app/public
    $filePath = 'storage/' . $fileName;

    // Update data proposal
    $adminId = 1; // Kembali ke admin untuk cek ulang
    $proposal->update([
        'file_proposal' => $filePath, // Ganti dengan file perbaikan
        'status' => 'pending_admin', // Kembali ke proses admin
        'current_reviewer_id' => $adminId, // Kembali ke admin
        'file_revisi' => null, // Reset file revisi (karena sudah diperbaiki)
        'catatan_revisi' => null, // Reset catatan revisi
    ]);

    return back()->with('success', 'Proposal berhasil diajukan ulang dan menunggu persetujuan admin.');
}
function tanggalIndo($tanggal) {
    $bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $date = date('Y-m-d', strtotime($tanggal));
    $explode = explode('-', $date);
    return $explode[2] . ' ' . $bulan[(int)$explode[1] - 1] . ' ' . $explode[0];
}
public function updateDana(Request $request, $id)
{
    $request->validate([
        'sisa_dana' => 'required|numeric|min:0'
    ]);

    $proposal = Proposal::findOrFail($id);
    $proposal->update(['sisa_dana' => $request->sisa_dana]);

    return redirect()->route('admin.aturDana')->with('success', 'Sisa dana berhasil diperbarui.');
}
public function aturDana()
{
    $proposals = Proposal::where('status', 'acc')->with('user')->get(); // hanya yang ACC
    return view('pages.admin.guru.index', compact('proposals'));
}


}