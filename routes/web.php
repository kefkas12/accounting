<?php

use App\Http\Controllers\AksesController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\MobilGemilangController;
use App\Http\Controllers\PdiController;
use App\Http\Controllers\PemasanganBanController;
use App\Http\Controllers\PenerimaanUnitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReceiveItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\ServiceActivityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupirController;
use App\Http\Controllers\TrainingUnitController;
use App\Http\Controllers\TransaksiBanController;
use App\Http\Controllers\TransaksiBarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\TransaksiBan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;

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

Route::get('/', function(){
    $data['sidebar'] = 'dashboard';
    return view('welcome',$data);  
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::get('/cloud/jarak', 'App\Http\Controllers\ApiController@jarak');
Route::get('/cloud/suhu', 'App\Http\Controllers\ApiController@suhu');

Route::controller(ProfilController::class)->prefix('profil')->group(function () {
	Route::get('/', 'index');
	Route::get('/password', 'password');
	Route::get('/company', 'company');

	Route::post('/', 'create');
	Route::post('/password', 'create_password');
	Route::post('/company', 'create_company');
});

Route::controller(PembelianController::class)->prefix('pembelian')->group(function () {
	Route::get('/', 'index');
	Route::get('/faktur', 'faktur');
	Route::get('/pemesanan', 'pemesanan');
	Route::get('/penawaran', 'penawaran');

	Route::get('/penawaran/{id}', 'penawaran');
	Route::get('/penawaran/cetak/{id}', 'cetak_penawaran');
	Route::get('/penawaran/pemesanan/{id}', 'penawaran_pemesanan');
	Route::get('/pemesanan/pengiriman/{id}', 'pemesanan_pengiriman');
	Route::get('/pemesanan/faktur/{id}', 'pemesanan_faktur');
	Route::get('/faktur/{id}', 'faktur');

	Route::get('/detail/{id}', 'detail');
	Route::get('/pembayaran/{id}', 'pembayaran');
	Route::get('/receive_payment/{id}','receive_payment');
	Route::post('/pembayaran', 'penerimaan_pembayaran');
	Route::post('/hapus/{id}', 'hapus');
	Route::post('/faktur', 'insert_faktur');
	Route::post('/faktur/{id}', 'update_faktur');
	
	Route::post('/penawaran', 'insert_penawaran');
	Route::post('/penawaran/{id}', 'update_penawaran');
	Route::post('/penawaran/pemesanan/{id}', 'insert_penawaran_pemesanan');
	Route::post('/pemesanan/pengiriman/{id}', 'insert_pemesanan_pengiriman');
	Route::post('/pemesanan/faktur/{id}', 'insert_pemesanan_faktur');

	Route::post('/pemesanan', 'insert_pemesanan');
});

Route::controller(PenawaranController::class)->prefix('penawaran')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'penawaran');
	Route::get('/{id}', 'penawaran');
	Route::get('/pemesanan/{id}', 'penawaran_pemesanan');
	Route::post('/', 'insert_penawaran');
	Route::post('/{id}', 'update_penawaran');
	Route::post('/pemesanan/{id}', 'insert_penawaran_pemesanan');
	Route::post('/pengiriman/{id}', 'insert_penawaran_pengiriman');

	Route::get('/detail/{id}', 'detail');
});

// Route::controller(PenawaranController::class)->prefix('penawaran')->group(function () {
// 	Route::get('/', 'index');
// 	Route::get('/insert', 'penawaran');
// 	Route::get('/{id}', 'penawaran');
// 	Route::get('/pemesanan/{id}', 'penawaran_pemesanan');
// 	Route::post('/', 'insert_penawaran');
// 	Route::post('/{id}', 'update_penawaran');
// 	Route::post('/pemesanan/{id}', 'insert_penawaran_pemesanan');
// 	Route::post('/pengiriman/{id}', 'insert_penawaran_pengiriman');
	
// 	Route::get('/detail/{id}', 'detail');
// });

Route::controller(PenjualanController::class)->prefix('penjualan')->group(function () {
	Route::get('/', 'index');
	Route::get('/penawaran', 'penawaran');
	Route::get('/pemesanan', 'pemesanan');
	Route::get('/penagihan', 'penagihan');

	Route::get('/penawaran/{id}', 'penawaran');
	Route::get('/penawaran/cetak/{id}', 'cetak_penawaran');
	Route::get('/penawaran/pemesanan/{id}', 'penawaran_pemesanan');
	Route::get('/pemesanan/pengiriman/{id}', 'pemesanan_pengiriman');
	Route::get('/pemesanan/penagihan/{id}', 'pemesanan_penagihan');
	Route::get('/pengiriman/penagihan/{id}', 'pengiriman_penagihan');
	Route::get('/penagihan/{id}', 'penagihan');

	Route::get('/detail/{id}', 'detail');
	Route::get('/pembayaran/{id}', 'pembayaran');
	Route::get('/receive_payment/{id}','receive_payment');
	Route::post('/pembayaran', 'penerimaan_pembayaran');
	Route::post('/penagihan', 'insert_penagihan');
	Route::post('/penagihan/{id}', 'update_penagihan');

	Route::post('/penawaran', 'insert_penawaran');
	Route::post('/penawaran/{id}', 'update_penawaran');
	Route::post('/penawaran/pemesanan/{id}', 'insert_penawaran_pemesanan');
	Route::post('/penawaran/pengiriman/{id}', 'insert_penawaran_pengiriman');
	Route::post('/pemesanan/pengiriman/{id}', 'insert_pemesanan_pengiriman');
	Route::post('/pemesanan/penagihan/{id}', 'insert_pemesanan_penagihan');
	Route::post('/pengiriman/penagihan/{id}', 'insert_pengiriman_penagihan');

	Route::post('/pemesanan', 'insert_pemesanan');

	Route::get('/cetak/surat_jalan/{id}','cetak_surat_jalan');
	Route::get('/cetak/penagihan/{id}','cetak_penagihan');
	Route::post('hapus/{id}','hapus');

	Route::get('/approve/{id}','approve');
	// Route::post('/{id}', 'edit');
});

Route::controller(PelangganController::class)->prefix('pelanggan')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/{status}/{id}', 'detail');
});

Route::controller(SupplierController::class)->prefix('supplier')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/{status}/{id}', 'detail');	
});

Route::controller(ProdukController::class)->prefix('produk')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/{status}/{id}', 'detail');	
});

Route::controller(GudangController::class)->prefix('gudang')->group(function () {
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/{status}/{id}', 'detail');	
});

Route::controller(AkunController::class)->prefix('akun')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/{status}/{id}', 'detail');	
});

Route::controller(JurnalController::class)->prefix('jurnal')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');
	Route::get('/approve/{id}', 'approve');
	Route::get('/approve/{id}/{status}', 'approve');
	Route::get('/cancel/{id}', 'cancel');
	Route::get('/{status}/{id}', 'detail');
	Route::post('/{status}/{id}', 'detail');
});

Route::controller(LaporanController::class)->prefix('laporan')->group(function () {
	Route::get('/', 'index');
	Route::get('/jurnal', 'jurnal');
	Route::get('/neraca_old', 'neraca');

	Route::get('/neraca', 'neraca_new');
	Route::post('/neraca', 'neraca_new');

	Route::get('/buku_besar', 'buku_besar');
	Route::get('/laba_rugi', 'laba_rugi');

	Route::get('/penjualan/{jenis}', 'penjualan');

	Route::get('/pembelian/{jenis}', 'pembelian');
});

Route::controller(PengaturanController::class)->prefix('pengaturan')->group(function () {
	Route::get('/', 'index');
	
	Route::get('/pengguna', 'pengguna');
	Route::get('/pengguna/insert', 'form_pengguna');
	Route::post('/pengguna/insert', 'insert_form_pengguna');

	Route::get('/pengguna/edit/{id}', 'form_pengguna');
	Route::post('/pengguna/edit/{id}', 'edit_form_pengguna');

	Route::get('/pengguna/hapus/{id}', 'hapus_form_pengguna');

	Route::get('/perusahaan', 'perusahaan');
	Route::get('/perusahaan/insert', 'form_perusahaan');
	Route::post('/perusahaan/insert', 'insert_form_perusahaan');

	Route::get('/approval', 'approval');
	Route::get('/approval/insert', 'form_approval');
	Route::post('/approval/insert', 'insert_form_approval');
});

Route::group([], __DIR__.'/routes_superadmin.php');


/*
Route::controller(KaryawanController::class)->group(function () {
	Route::get('/karyawan', 'index');
	Route::post('/insert_karyawan', 'insert');
	Route::post('/edit_karyawan/{id}', 'edit');
	Route::get('/status_karyawan/{id}', 'status');
});

Route::controller(RoleController::class)->group(function () {
	Route::get('/role', 'index');
	Route::post('/role', 'insert');
	Route::post('/role/{id}', 'edit');
	Route::get('/delete_role/{id}', 'delete');
});

Route::controller(PermissionController::class)->group(function () {
	Route::get('/permission/{id}', 'index');
	Route::post('/permission/{id}', 'edit');
	Route::get('/delete_role/{id}', 'delete');
});

Route::controller(AksesController::class)->group(function () {
	Route::get('/akses/{id}', 'index');
	Route::post('/edit_akses/{id}', 'edit');
});

Route::controller(UserController::class)->group(function () {
	Route::post('/user/import', 'import');
	Route::get('/user', 'index');
	Route::get('/user/{id}', 'cari');
	Route::post('/user', 'insert');
	
	Route::post('/user/{id}', 'edit');

	Route::get('/status_user/{id}', 'status');
	Route::get('/konsumen/nama/{id}', "nama");
	Route::get('/konsumen/unit/{id}', "unit");
	
	Route::get('/konsumen/browse/{keyword}', 'browse');
});
Route::controller(ReceiveItemController::class)->group(function () {
	Route::get('/receive_item', 'index');
	Route::get('/receive_item/{id}', 'cari');
	Route::post('/receive_item', 'insert');
	Route::post('/receive_item/{id}', 'edit');
	Route::get('/receive_item/pengirim/{id}', 'pengirim');
	Route::get('/receive_item/penerima/{id}', 'penerima');
	Route::get('/receive_item/delete/{id}', 'delete');
	
	Route::get('/receive_item/unit/{id}', 'unit');
	Route::post('/receive_item/unit/{id}', 'insert_unit');
	Route::post('/receive_item/unit/edit/{id}', 'edit_unit');
	Route::get('/receive_item/unit/delete/{id}', 'delete_unit');
	
	
});

Route::controller(UnitController::class)->group(function () {
    Route::get('/unit/mutasi/{id}', 'mutasi');
	Route::post('/unit/import', 'import');
	Route::get('/unit', 'index');
	Route::get('/unit/{id}', 'cari');
	Route::get('/unit/browse/{keyword}/{posisi}/{method}', 'browse');
	Route::post('/unit/{id}', 'edit');
	
	Route::get('/unit/delete/{id}', 'delete');

	Route::get('/unit/serah_terima/{id}', 'serah_terima');
	Route::post('/edit_nama_pemilik/{id}', 'edit_nama_pemilik');
});

Route::controller(PenerimaanUnitController::class)->group(function () {
	Route::get('/penerimaan_unit', 'index');
	Route::post('/insert_penerimaan_unit', 'insert');
	Route::post('/edit_penerimaan_unit/{id}', 'edit');
	Route::get('/delete_penerimaan_unit/{id}', 'delete');
	Route::get('/penerimaan_unit/unit/{id}', 'unit');
	Route::get('/penerimaan_unit/no_seri_unit/{id}', 'no_seri_unit');
	Route::get('/penerimaan_unit/pemilik_terakhir/{nama}', 'pemilik_terakhir');
	Route::get('/penerimaan_unit/pengirim/{id}', 'pengirim');
	Route::get('/penerimaan_unit/penerima/{id}', 'penerima');
});


Route::controller(ServiceController::class)->group(function () {
	Route::get('/service', 'index');
	Route::get('/service/{id}', 'service');
	Route::post('/service/{id}', 'edit');
	// Route::get('/service_load', 'load');
	Route::POST('/service_load', 'load');
	Route::post('/insert_service', 'insert');
	Route::post('/edit_service/{id}', 'edit');
	Route::post('/import_service', 'import');
	Route::get('/service/delete/{id}', 'delete');
	Route::get('/service/no_seri_unit/{no_seri_unit}', 'no_seri_unit');
	Route::get('/service/no_job_request/{no_job_request}', 'no_job_request');
	Route::get('/service/mekanik/{nama}', 'mekanik');
	Route::post('/service/edit_mekanik/{id}', 'edit_mekanik');
	Route::get('/service/customer/{nama}', 'customer');
	Route::get('/service/no_service_bill_manual/{no_service_bill_manual}', 'no_service_bill_manual');
	
	Route::get('/service/foto/{id}', 'foto');
});

Route::controller(JobRequestController::class)->group(function () {
	Route::get('/job_request', 'index');
	Route::get('/job_request/{id}', 'cari');
	Route::post('/job_request', 'insert');
	Route::post('/job_request/{id}', 'edit');
	Route::get('/job_request/service/{id}', 'service');
	Route::post('/job_request/service/{id}', 'add_service');
	Route::post('/job_request/service/edit/{id}', 'edit_service');
	Route::get('/job_request/service/delete/{id}', 'delete_service');
	
	Route::get('/job_request/status/{id}', 'status_job_request');
	Route::post('/job_request/status/{id}', 'insert_status_job_request');

	Route::post('/insert_job_request', 'insert');
	Route::post('/edit_job_request_mekanik/{id}', 'edit_mekanik');
	Route::post('/edit_service_detail/{id}/{no}', 'edit_service');
	Route::get('/job_request/delete/{id}', 'delete');
	Route::get('/job_request/no_seri_unit/{no_seri_unit}', 'no_seri_unit');
	Route::get('/job_request/model_unit/{no_seri_unit}', 'model_unit');
	Route::get('/job_request/service_detail/no_seri_unit/{no_seri_unit}', 'no_seri_unit');
	Route::get('/job_request/nomor_job_request/{cabang}', 'nomor_job_request_cabang');
	Route::get('/job_request/customer/{nama}', 'customer');
	Route::get('/job_request/mekanik/{nama}/{id_job_request}', 'mekanik');
	Route::post('/import_job_request', 'import');
	Route::post('/job_request/edit_mekanik/{id}', 'edit_mekanik');
	Route::post('/edit_no_seri_unit/{id}', 'edit_no_seri_unit');
	Route::post('/edit_tgl_request/{id}', 'edit_tgl_request');
});

Route::controller(SettingController::class)->group(function () {
	Route::get('/setting', 'index');
	Route::post('/setting/{status}','insert');
	Route::post('/setting/edit/{status}/{id}','update');
	Route::get('/setting/delete/{status}/{id}','delete');
	
	Route::post('/insert_setting_perusahaan', 'insert_perusahaan');
	Route::post('/edit_setting_perusahaan/{id}', 'edit_perusahaan');
	Route::post('/insert_setting_cabang', 'insert_cabang');
	Route::post('/edit_setting_cabang/{id}', 'edit_cabang');
	Route::post('/insert_setting_cabang_perusahaan', 'insert_cabang_perusahaan');
	Route::post('/edit_setting_cabang_perusahaan/{id}', 'edit_cabang_perusahaan');
	Route::post('/insert_setting_jabatan', 'insert_jabatan');
	Route::post('/edit_setting_jabatan/{id}', 'edit_jabatan');
	Route::post('/insert_setting_model_unit', 'insert_model_unit');
	Route::post('/edit_setting_model_unit/{id}', 'edit_model_unit');
});

Route::controller(ExportController::class)->group(function () {
	Route::get('export/service', 'export_service');
	Route::get('export/unit', 'export_unit');
	Route::post('export/service', 'export_service');
	Route::post('export/unit', 'export_unit');
	Route::get('export/service/{filter}/{dari}/{sampai}/{cabang}', 'service_export');
	Route::get('export/unit/{filter}/{dari}/{sampai}/{cabang}', 'unit_export');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/