<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MeusDadosController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\EBController;
use App\Http\Controllers\EBETController;
use App\Http\Controllers\LoginsController;
use App\Http\Controllers\MembrosController;
use App\Http\Controllers\ETController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EquipamentosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UISettingsController;


Route::get('/', function () {return redirect('sign-in');})->middleware('guest');
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

# LOGIN 
Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {return view('sessions.password.verify');})->middleware('guest')->name('verify');
Route::get('reset-password/{token}', function ($token) {return view('sessions.password.reset', ['token' => $token]);})->middleware('guest')->name('password.reset');

# UserController
	Route::get('user-profile', [UserController::class, 'index'])->middleware('auth')->name('user-profile');
	Route::post('user-profile', [UserController::class, 'update'])->middleware('auth')->name('user.update');
	Route::post('user-profile/password', [UserController::class, 'passwordUpdate'])->middleware('auth')->name('password.change');

# RolesController
	# READ 
		Route::get('roles', [RolesController::class, 'index'])->middleware('auth')->name('roles');
	# CREATE
		Route::get('new-role', [RolesController::class, 'create'])->middleware('auth')->name('add.role');
		Route::post('new-role', [RolesController::class, 'store'])->middleware('auth');
	# UPDATE
		Route::post('edit-role/{id}', [RolesController::class, 'update'])->middleware('auth');
		Route::get('edit-role/{id}', [RolesController::class, 'edit'])->middleware('auth')->name('edit.role');
	# DELETE
		Route::post('roles/{id}', [RolesController::class, 'destroy'])->middleware('auth')->name('delete.role');
# Perfil
	Route::get('meuPerfil', [PerfilController::class, 'meuPerfil'])->middleware('auth')->name('meuPerfil');
	Route::get('configPerfil', [PerfilController::class, 'configPerfil'])->middleware('auth')->name('configPerfil');

# PainelController
	# READ
		Route::get('painelRelatorios', [PainelController::class, 'painelRelatorios'])->middleware('auth')->name('painelRelatorios');
		Route::get('painelAjustes', [PainelController::class, 'painelAjustes'])->middleware('auth')->name('painelAjustes');
		Route::get('painelUsuarios', [PainelController::class, 'painelUsuarios'])->middleware('auth')->name('painelUsuarios');
		Route::get('painelAjuda', [PainelController::class, 'painelAjuda'])->middleware('auth')->name('painelAjuda');

# MeusDadosController
	# READ
		Route::get('inicio', [MeusDadosController::class, 'inicio'])->middleware('auth')->name('inicio');
		Route::get('meusTrabalhos', [MeusDadosController::class, 'meusTrabalhos'])->middleware('auth')->name('meusTrabalhos');
		Route::get('meusMateriais', [MeusDadosController::class, 'meusMateriais'])->middleware('auth')->name('meusMateriais');
		Route::get('meusServicos', [MeusDadosController::class, 'meusServicos'])->middleware('auth')->name('meusServicos');

	# CREATE
		Route::post('/novoMeuTrabalho', [MeusDadosController::class, 'novoMeuTrabalho'])->middleware(['auth', 'verified']);

	# UPDATE
		Route::post('/updateMeusMateriais/{id}', [MeusDadosController::class, 'updateMeusMateriais'])->middleware(['auth', 'verified']);
		Route::post('/updateMeusServicos/{id}', [MeusDadosController::class, 'updateMeusServicos'])->middleware(['auth', 'verified']);
		Route::post('/updateTrabalhoValorCobrado/{id}', [MeusDadosController::class, 'updateTrabalhoValorCobrado'])->middleware(['auth', 'verified']);
 
	# DELETE  
		Route::get('/apagarMeuServico/{id}', [MeusDadosController::class, 'apagarMeuServico'])->middleware(['auth', 'verified']);
		Route::get('/apagarMeuMaterial/{id}', [MeusDadosController::class, 'apagarMeuMaterial'])->middleware(['auth', 'verified']);
		Route::get('/apagarMeuTrabalho/{id}', [MeusDadosController::class, 'apagarMeuTrabalho'])->middleware(['auth', 'verified']);
		
# EquipamentosController
	# READ
		Route::get('meusEquipamentos', [EquipamentosController::class, 'meusEquipamentos'])->middleware('auth')->name('meusEquipamentos');
	
	# CREATE
		Route::post('/novoEquipamento', [EquipamentosController::class, 'novoEquipamento'])->middleware(['auth', 'verified'])->name('novoEquipamento');
	# UPDATE
		Route::post('/updateEquipamento', [EquipamentosController::class, 'updateEquipamento'])->middleware(['auth', 'verified'])->name('updateEquipamento');
	# DELETE
		Route::get('/apagarEquipamento/{id}', [EquipamentosController::class, 'apagarEquipamento'])->middleware(['auth', 'verified'])->name('apagarEquipamento');

# ETController
	# READ
		Route::get('/editarMeuTrabalho/{id}', [ETController::class, 'editarMeuTrabalho'])->middleware(['auth', 'verified'])->name('editarMeuTrabalho');
		
	# CREATE
		Route::post('/ETaddMaterial/{id}', [ETController::class, 'ETaddMaterial'])->middleware(['auth', 'verified']);
		Route::post('/ETaddServico/{id}', [ETController::class, 'ETaddServico'])->middleware(['auth', 'verified']);
		
	# UPDATE
		Route::post('/update-order-s', [ETController::class, 'updateOrderS'])->name('update.orderS');
		Route::post('/update-order-e', [ETController::class, 'updateOrderE'])->name('update.orderE');
		Route::post('/update-order-m', [ETController::class, 'updateOrderM'])->name('update.orderM');
		Route::post('/updateETServicoT', [ETController::class, 'updateETServicoT'])->name('updateETServicoT');
		Route::post('/updateETMaterialQTD', [ETController::class, 'updateETMaterialQTD'])->name('updateETMaterialQTD');
		
	# DELETE
		Route::get('/ETapagarMeuMaterial/{trab}/{id}', [ETController::class, 'ETapagarMeuMaterial'])->middleware(['auth', 'verified']);
		Route::get('/ETapagarMeuServico/{trab}/{id}', [ETController::class, 'ETapagarMeuServico'])->middleware(['auth', 'verified']);
		
# FinanceiroController
	# READ
		Route::get('dre', [FinanceiroController::class, 'dre'])->middleware('auth')->name('dre');
		Route::get('producao', [FinanceiroController::class, 'producao'])->middleware('auth')->name('producao');
	# CREATE
		Route::post('/DREnovoPeriodo', [FinanceiroController::class, 'DREnovoPeriodo'])->middleware(['auth', 'verified']);
		Route::post('/novoProducao', [FinanceiroController::class, 'novoProducao'])->middleware(['auth', 'verified']);

	# UPDATE
		Route::post('refatorarProducao', [FinanceiroController::class, 'refatorarProducao'])->middleware('auth')->name('refatorarProducao');
	# DELETE
		Route::get('/apagarProducao/{id}', [ETController::class, 'apagarProducao'])->middleware(['auth', 'verified']);

# BaseController
	# READ
		Route::get('consultaBaseTrabalhos', [BaseController::class, 'consultaBaseTrabalhos'])->middleware('auth')->name('consultaBaseTrabalhos');
		Route::get('consultaBaseMateriais', [BaseController::class, 'consultaBaseMateriais'])->middleware('auth')->name('consultaBaseMateriais');
		Route::get('consultaBaseServicos', [BaseController::class, 'consultaBaseServicos'])->middleware('auth')->name('consultaBaseServicos');
		Route::get('/getTrabalhoDetails/{id}', [BaseController::class, 'getTrabalhoDetails'])->middleware(['auth', 'verified']);
	# CREATE
		Route::get('/copiaBaseTrabalhos/{id}', [BaseController::class, 'copiaBaseTrabalhos'])->middleware(['auth', 'verified']);
		Route::get('/copiaBaseMateriais/{id}', [BaseController::class, 'copiaBaseMateriais'])->middleware(['auth', 'verified']);
		Route::get('/copiaBaseServicos/{id}', [BaseController::class, 'copiaBaseServicos'])->middleware(['auth', 'verified']);

# AdminController
	Route::get('adminInicio', [AdminController::class, 'adminInicio'])->middleware('auth')->name('adminInicio');
	Route::get('serverRestart', [AdminController::class, 'serverRestart'])->middleware('auth')->name('serverRestart');
	Route::get('serverRestartRUN', [AdminController::class, 'serverRestartRUN'])->middleware('auth')->name('serverRestartRUN');

# MembrosController
	# READ
		Route::get('usuariosCadastrados', [MembrosController::class, 'usuariosCadastrados'])->middleware('auth')->name('usuariosCadastrados');
		Route::get('empresasCadastradas', [MembrosController::class, 'empresasCadastradas'])->middleware('auth')->name('empresasCadastradas');
	# UPDATE
		Route::post('updateUsuarios', [MembrosController::class, 'updateUsuarios'])->middleware(['auth', 'verified'])->name('updateUsuarios');
		Route::post('updateDadosEmpresa/{id}', [MembrosController::class, 'updateDadosEmpresa'])->middleware(['auth', 'verified'])->name('updateDadosEmpresa');
	# DELETE
		Route::get('/adminApagarUsuario/{id}', [MembrosController::class, 'adminApagarUsuario'])->middleware(['auth', 'verified']);
		Route::get('/adminApagarEmpresa/{id}', [MembrosController::class, 'adminApagarEmpresa'])->middleware(['auth', 'verified']);
	# CREATE
		Route::post('/adminNovoUsuario', [MembrosController::class, 'adminNovoUsuario'])->middleware(['auth', 'verified']);
		Route::post('/novaEmpresa', [MembrosController::class, 'novaEmpresa'])->middleware(['auth', 'verified']);
		

# EBController
	# READ
		Route::get('EBTrabalhos', [EBController::class, 'EBTrabalhos'])->middleware('auth')->name('EBTrabalhos');
		Route::get('EBMateriais', [EBController::class, 'EBMateriais'])->middleware('auth')->name('EBMateriais');
		Route::get('EBServicos', [EBController::class, 'EBServicos'])->middleware('auth')->name('EBServicos');
	
	# UPDATE
		Route::post('EBTrabalhosUpdate/{id}', [EBController::class, 'EBTrabalhosUpdate'])->middleware('auth')->name('EBTrabalhosUpdate');
		Route::post('EBTrabalhosUpdateCheckboxState/{id}', [EBController::class, 'EBTrabalhosUpdateCheckboxState'])->middleware('auth')->name('EBTrabalhosUpdateCheckboxState');
		Route::post('EBMateriaisUpdate/{id}', [EBController::class, 'EBMateriaisUpdate'])->middleware('auth')->name('EBMateriaisUpdate');
		Route::post('EBServicosUpdate/{id}', [EBController::class, 'EBServicosUpdate'])->middleware('auth')->name('EBServicosUpdate');
	
	# CREATE
		Route::post('EBTrabalhosNovo', [EBController::class, 'EBTrabalhosNovo'])->middleware('auth')->name('EBTrabalhosNovo');
		// Route::post('EBTrabalhosAdd/{id}', [EBController::class, 'EBTrabalhosAdd'])->middleware('auth')->name('EBTrabalhosAdd');
		// Route::post('EBMateriaisAdd/{id}', [EBController::class, 'EBMateriaisAdd'])->middleware('auth')->name('EBMateriaisAdd');
		// Route::post('EBServicosAdd/{id}', [EBController::class, 'EBServicosAdd'])->middleware('auth')->name('EBServicosAdd');

	# DELETE
		Route::get('EBTrabalhosDelete/{id}', [EBController::class, 'EBTrabalhosDelete'])->middleware('auth')->name('EBTrabalhosDelete');
		Route::get('EBMateriaisDelete/{id}', [EBController::class, 'EBMateriaisDelete'])->middleware('auth')->name('EBMateriaisDelete');
		Route::get('EBServicosDelete/{id}', [EBController::class, 'EBServicosDelete'])->middleware('auth')->name('EBServicosDelete');

# EBETController
	# READ
		Route::get('EBET/{id}', [EBETController::class, 'EBET'])->middleware('auth')->name('EBET');
	# CREATE
		Route::post('EBETMaterialAdd/{id}', [EBETController::class, 'EBETMaterialAdd'])->middleware('auth')->name('EBETMaterialAdd');
		Route::post('EBETMaterialNovo/{id}', [EBETController::class, 'EBETMaterialNovo'])->middleware('auth')->name('EBETMaterialNovo');
		Route::post('EBETServicoAdd/{id}', [EBETController::class, 'EBETServicoAdd'])->middleware('auth')->name('EBETServicoAdd');
		Route::post('EBETServicoNovo/{id}', [EBETController::class, 'EBETServicoNovo'])->middleware('auth')->name('EBETServicoNovo');
	# UPDATE
		Route::post('EBETMaterialEdit', [EBETController::class, 'EBETMaterialEdit'])->middleware('auth')->name('EBETMaterialEdit');
		Route::post('EBETServicoEdit', [EBETController::class, 'EBETServicoEdit'])->middleware('auth')->name('EBETServicoEdit');
		Route::post('EBETServicolOrder', [EBETController::class, 'EBETServicolOrder'])->middleware('auth')->name('EBETServicolOrder');
	# DELETE
		Route::get('EBETMaterialDelete/{trab}/{id}', [EBETController::class, 'EBETMaterialDelete'])->middleware('auth')->name('EBETMaterialDelete');
		Route::get('EBETServicoDelete/{trab}/{id}', [EBETController::class, 'EBETServicoDelete'])->middleware('auth')->name('EBETServicoDelete');
	


# LoginsController
	Route::get('relatorioLogins', [LoginsController::class, 'relatorioLogins'])->middleware('auth')->name('relatorioLogins');
	Route::get('loginTokens', [LoginsController::class, 'loginTokens'])->middleware('auth')->name('loginTokens');
	Route::get('/generate-token', function () {
		Artisan::call('generate:token');
		return redirect()->back()->with('message', 'Token gerado com sucesso!');
	})->name('generate.token');



	## OUTROS 
	# Salvar dados da sidebar na session
	Route::post('/save-ui-settings', [UISettingsController::class, 'saveSettings'])->name('save-ui-settings');
	# Visualizar os dados guardados na Session do Laravel
	Route::get('/view-session', [UISettingsController::class, 'viewSessionData'])->name('view-session');
	


	