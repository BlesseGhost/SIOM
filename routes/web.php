<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengumumanSekolahController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PembinaProposalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile');
    Route::put('/update-profile', [UserController::class, 'update'])->name('update.profile');
    Route::get('/edit-password', [UserController::class, 'editPassword'])->name('ubah-password');
    Route::patch('/update-password', [UserController::class, 'updatePassword'])->name('update-password');
    Route::post('/proposal/generate', [ProposalController::class, 'generate'])->name('proposal.generate');
    Route::delete('/proposal/{id}', [ProposalController::class, 'hapusProposal'])->name('proposal.hapus');
    Route::post('/siswa/materi', [ProposalController::class, 'generate'])->name('proposal.generate');
    Route::post('/siswa/anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
    Route::get('/proposal/download/{id}', [ProposalController::class, 'download'])->name('proposal.download');
});
Route::group(['middleware' => ['auth', 'checkRole:siswa']], function () {
    Route::get('/siswa/dashboard', [HomeController::class, 'siswa'])->name('siswa.dashboard');
    
    // Form pengajuan proposal
    Route::get('/siswa/proposal/form', [ProposalController::class, 'index'])->name('proposal.form');
    
    // Aksi generate proposal (submit form)
    
    
    // List proposal yang diajukan user
    Route::get('/siswa/proposal/list', [ProposalController::class, 'listUserProposal'])->name('siswa.proposal');

    // Download file proposal
    Route::post('/proposal/revisi/kirim-ulang/{id}', [ProposalController::class, 'resubmit'])->name('proposal.resubmit')->middleware(['auth', 'checkRole:siswa']);

});
// Pembina Routes
Route::prefix('pembina')->name('pembina.')->middleware('auth')->group(function () {
    Route::get('/proposal', [\App\Http\Controllers\PembinaProposalController::class, 'index'])->name('proposal.index');
    Route::post('/proposal/acc/{id}', [\App\Http\Controllers\PembinaProposalController::class, 'acc'])->name('proposal.acc');
    Route::get('/proposal/revisi/{id}', [\App\Http\Controllers\PembinaProposalController::class, 'formRevisi'])->name('proposal.revisi.form');
    Route::post('/proposal/revisi/{id}', [\App\Http\Controllers\PembinaProposalController::class, 'revisi'])->name('proposal.revisi');
});
Route::group(['middleware' => ['auth', 'checkRole:admin']], function () {
    Route::get('/admin/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
    Route::resource('jurusan', JurusanController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('user', UserController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('pengumuman-sekolah', PengumumanSekolahController::class);
    Route::resource('pengaturan', PengaturanController::class);
    Route::get('/admin/proposal', [ProposalController::class, 'listProposalAdmin'])->name('admin.proposal.list');
    Route::get('/admin/proposal/acc/{id}', [ProposalController::class, 'accByAdmin'])->name('proposal.acc.admin');
    Route::post('/admin/proposal/revisi/{id}', [ProposalController::class, 'revisiByAdmin'])->name('proposal.revisi.admin');
    Route::get('/admin/atur-dana', [ProposalController::class, 'aturDana'])->name('admin.aturDana');
    Route::put('/admin/update-dana/{id}', [ProposalController::class, 'updateDana'])->name('admin.updateDana');
});

