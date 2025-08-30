<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ControllerPerfil;
use App\Http\Controllers\SondagemController;
use App\Http\Controllers\SondagemInicialController;
use App\Http\Controllers\PerfilEstudanteController;
use App\Http\Controllers\EnsinoController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AtualizaPerfinEstudante;
use App\Http\Controllers\VisualizaPerfilController;
use App\Http\Controllers\ImprimeAlunoController;
use App\Http\Controllers\InserirPerfilEstudante;
use App\Http\Controllers\AtualizacaoController;
use App\Http\Controllers\AtualizacaoPerfilController;
use App\Http\Controllers\InserirEixoEstudanteController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\OrgaoController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\VisualizaInventarioEstudanteController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\GeneratePDFController;
use App\Http\Controllers\GenerateTemplatePDFController;
use App\Http\Controllers\SobreNosController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController; // Importação para os materiais
use App\Http\Controllers\GraficoMonitoramentoController;
use App\Http\Controllers\MonitoramentoAtividadeController;
use App\Http\Controllers\MonitoramentoController;
use App\Http\Controllers\RotinaCadastroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas da sua aplicação.
| Essas rotas são carregadas pelo RouteServiceProvider dentro do grupo "web".
|
*/

// Rota para download de materiais pedagógicos
Route::get('/download/material/{tipo}', [MaterialController::class, 'baixar'])->name('download.material');

// Rota raiz redireciona para /index
Route::get('/', function () {
    return redirect('/index');
});
Route::get('/index', function () {
    return view('index');
})->name('index');

// Rota para inserir inventário
use App\Http\Controllers\InserirEixoController;
Route::post('/sondagem/inserir-inventario/{id}', [InserirEixoController::class, 'store'])->name('inserir_inventario');

// Logout
use Illuminate\Http\Request;
Route::post('/logout', function (Request $request) {
    auth()->guard('funcionario')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('status', 'Sessão encerrada com sucesso!');
})->name('logout');

// =========================
// Rota protegida para dashboard (evita erro de view e mantém segurança)
Route::middleware(['auth:funcionario'])->get('/dashboard', function () {
    return view('dashboard'); // Crie a view se necessário
})->name('dashboard');
// =========================
// TESTE POST SIMPLES
Route::get('/formulario-teste', function () {
    return view('formulario_teste');
})->name('formulario.teste');

Route::post('/formulario-teste', function (Illuminate\Http\Request $request) {
    if ($request->filled(['nome', 'idade'])) {
        return back()->with('success', 'Recebido: ' . $request->nome . ', idade: ' . $request->idade);
    }
    return back()->with('error', 'Preencha todos os campos!');
})->name('formulario.teste.submit');
// =========================
// MONITORAMENTO EXEMPLO DEBUG
Route::get('/monitoramento/exemplo/{alunoId}', [\App\Http\Controllers\MonitoramentoAtividadeController::class, 'exemploMonitoramento']);
// =========================
// ROTA POST PARA SALVAR MONITORAMENTO
Route::post('/monitoramento/salvar', [\App\Http\Controllers\MonitoramentoAtividadeController::class, 'salvar'])->name('monitoramento.salvar');
// ROTA PARA BUSCAR ATIVIDADES CADASTRADAS
Route::get('/monitoramento/atividades-cadastradas/{aluno_id}', [\App\Http\Controllers\MonitoramentoAtividadeController::class, 'buscarAtividadesCadastradas'])->name('monitoramento.atividades-cadastradas');
// SONDAGEM
// =========================
Route::middleware(['auth:funcionario', 'funcao.especial'])->group(function () {
    // Exemplo de rota inicial da Sondagem
    Route::get('/sondagem/inicial', [SondagemController::class, 'inicial'])->name('sondagem.inicial');
    // Rota para exibir resultado do aluno em JSON
    Route::get('/sondagem/resultado-aluno/{alu_id}', [SondagemController::class, 'resultadoAluno']);
    // Rota para gerar o template PDF
    Route::get('/generate-template-pdf', [GenerateTemplatePDFController::class, 'generateTemplate'])->name('generate.template.pdf');
    // Rota para processar resultados dos três eixos
    Route::post('/sondagem/processa-resultados/{alunoId}', [\App\Http\Controllers\ProcessaResultadosController::class, 'processaTodosEixos'])->name('processa_resultados');
    Route::get('/sondagem/cadastra-inventario/{id}', [AlunoController::class, 'mostra_aluno_inventario'])->name('alunos.inventario');
    Route::post('/sondagem/inserir_inventario/{id}', [InserirEixoEstudanteController::class, 'inserir_eixo_estudante'])->name('inserir_inventario');
    Route::get('/sondagem/alunoturma/{id}', [InserirEixoEstudanteController::class, 'aluno_turma'])->name('aluno.turma');
    Route::get('/sondagem/inventario/{id}', [InserirEixoEstudanteController::class, 'inventario_parametro'])->name('perfil.inventario');
    // Rotas para os gráficos de monitoramento
    Route::get('/grafico/comunicacao/{alunoId}', [GraficoMonitoramentoController::class, 'graficoEixoComunicacao'])->name('grafico.comunicacao');
    Route::get('/sondagem/visualizar-inventario/{id}', [AlunoController::class, 'visualiza_aluno_inventario'])->name('visualizar.inventario');
    Route::get('/sondagem/inicial', [SondagemInicialController::class, 'inicial'])->name('sondagem.inicial');
    Route::get('/sondagem/continuada1', [SondagemInicialController::class, 'continuada1'])->name('sondagem.continuada1');
    Route::get('/sondagem/continuada2', [SondagemInicialController::class, 'continuada2'])->name('sondagem.continuada2');
    Route::get('/sondagem/final', [SondagemInicialController::class, 'final'])->name('sondagem.final');
    // Adicione aqui as demais rotas do menu Sondagem
});

// =========================
// ROTINA E MONITORAMENTO

// =========================
// ROTA FUNCIONAL PARA MONITORAMENTO DO ALUNO
Route::get('/monitoramento/aluno/{aluno_id}', [MonitoramentoController::class, 'rotina_monitoramento_aluno'])->name('rotina.monitoramento.aluno');
Route::get('/monitoramento/cadastrar/{id}', [MonitoramentoController::class, 'cadastrar_rotina_aluno'])->name('rotina.monitoramento.cadastrar');
Route::post('/monitoramento/salvar/{id}', [MonitoramentoController::class, 'salvar_rotina'])->name('rotina.monitoramento.salvar');

// =========================
// INDICATIVO DE ATIVIDADES
// =========================
// Rota para listagem de alunos no contexto de Indicativo Inicial
Route::get('/indicativo/alunos', [PerfilEstudanteController::class, 'listarAlunosIndicativo'])->name('indicativo.inicial.lista');
// Rota para o formulário de Indicativo Inicial de um aluno específico
Route::get('/indicativo/aluno/{id}/inicial', [MonitoramentoAtividadeController::class, 'indicativoInicial'])->name('indicativo.inicial');

// =========================
// PERFIL FAMÍLIA
// =========================
// Rota para listagem de alunos no contexto de Perfil Família
Route::get('/familia/alunos', [PerfilEstudanteController::class, 'listarAlunosFamilia'])->name('familia.inicial.lista');

// Rota para o perfil inicial de um aluno específico no contexto da família
Route::get('/familia/aluno/{id}/perfil', [\App\Http\Controllers\FamiliaController::class, 'perfilInicialAluno'])->name('familia.inicial');

// Rota restaurada para evitar erro em views antigas
Route::get('/rotina/visualizar/{id}', function($id) {
    return 'Visualizar rotina para aluno ' . $id;
})->name('rotina.monitoramento.visualizar');
// =========================
Route::middleware(['auth:funcionario', 'funcao.especial'])->group(function () {
    // Exemplo de rota inicial de Rotina e Monitoramento
    Route::get('/rotina_monitoramento/inicial', [PerfilEstudanteController::class, 'rotina_monitoramento_inicial'])->name('rotina.monitoramento.inicial');
    // Rota para cadastrar rotina de monitoramento do aluno
    Route::get('/rotina_monitoramento/cadastrar/{id}', [PerfilEstudanteController::class, 'cadastrar_rotina_aluno'])->name('rotina.monitoramento.cadastrar');
    // Adicione aqui as demais rotas do menu Rotina e Monitoramento
});

// Páginas estáticas
Route::get('/sobre-nos', [SobreNosController::class, 'sobreNos'])->name('sobre-nos');
Route::get('/contato', [ContatoController::class, 'contato'])->name('contato');

// Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Recuperação de senha padrão Laravel
Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

// Troca de senha no primeiro acesso
Route::get('/password/first-change', [AuthController::class, 'showFirstChangeForm'])->name('password.first.change');
Route::post('/password/first-change', [AuthController::class, 'processFirstChange']);

// Primeiro acesso (email + CPF)
Route::get('/primeiro-acesso', [AuthController::class, 'showPrimeiroAcessoForm'])->name('primeiro.acesso');
Route::post('/primeiro-acesso', [AuthController::class, 'primeiroAcesso']);

// Sondagem inicial
Route::get('/sondagem-inicial', [SondagemController::class, 'index'])->name('sondagem.inicial');

// Formulário (exemplo)
Route::get('/formulario', function () {
    return view('formulario');
})->name('formulario.view');

Route::post('/formulario-submit', function (Request $request) {
    // Processar os dados do formulário aqui
    return back()->with('success', 'Formulário enviado com sucesso!');
})->name('formulario.submit');

// Grupo de rotas para sondagens
Route::middleware(['auth:funcionario', 'funcao.especial'])->prefix('sondagem')->group(function () {
    // Rota para listar alunos do professor logado usando novo controller
    Route::get('/perfil-estudante', [\App\Http\Controllers\PerfilEstudanteIndependenteController::class, 'index'])->name('perfil.estudante');
    
    // Nova rota independente
    Route::get('/perfil-estudante-independente', [\App\Http\Controllers\PerfilEstudanteIndependenteController::class, 'index'])->name('perfil.estudante.independente');
    
    // Rotas de perfil
    Route::get('/alunos/{id}', [AlunoController::class, 'index'])->name('alunos.index');
    Route::get('/perfil-estudante/{id}', [PerfilEstudanteController::class, 'mostrar'])->name('perfil.estudante.mostrar');
    Route::get('/visualizar-perfil/{id}', [VisualizaPerfilController::class, 'visualizaPerfil'])->name('visualizar.perfil');
    Route::get('/editar-perfil/{id}', [VisualizaPerfilController::class, 'editaPerfil'])->name('editar.perfil');
    Route::put('/atualizaperfil/{id}', [AtualizacaoPerfilController::class, 'AtualizaPerfil'])->name('atualiza.perfil.estudante');
    Route::post('/inserir_perfil', [InserirPerfilEstudante::class, 'inserir_perfil_estudante'])->name('inserir_perfil');

    // Rota para processar resultados dos três eixos
    Route::post('/processa-resultados/{alunoId}', [\App\Http\Controllers\ProcessaResultadosController::class, 'processaTodosEixos'])->name('processa_resultados');
    Route::get('/eixos-estudante/{fase?}', [\App\Http\Controllers\FaseVerificacaoController::class, 'index'])
       ->name('eixos.alunos')
       ->where('fase', 'inicial|continuada1|continuada2|continuada3|final');

    Route::get('/cadastra-inventario/{id}', [AlunoController::class, 'mostra_aluno_inventario'])->name('alunos.inventario');
    // Route removida para evitar conflito de nome
// Route::post('/inserir_inventario/{id}', [InserirEixoEstudanteController::class, 'inserir_eixo_estudante'])->name('inserir_inventario');

    Route::get('/visualizar-inventario/{id}', [AlunoController::class, 'visualiza_aluno_inventario'])->name('visualizar.inventario');
    // Route::get('/inicial', [AlunoController::class, 'index'])->name('alunos.index');
    Route::get('/inicial', [SondagemInicialController::class, 'inicial'])->name('sondagem.inicial');
    Route::get('/continuada1', [SondagemInicialController::class, 'continuada1'])->name('sondagem.continuada1');
    Route::get('/continuada2', [SondagemInicialController::class, 'continuada2'])->name('sondagem.continuada2');
    Route::get('/final', [SondagemInicialController::class, 'final'])->name('sondagem.final');
});

//criando grupo de rota para rotina e monitoramento
// Rotas para rotina e monitoramento
Route::get('/rotina_monitoramento/aluno/{id}', [\App\Http\Controllers\PerfilEstudanteController::class, 'rotina_monitoramento_aluno'])->name('rotina.monitoramento.aluno');
Route::get('/rotina/cadastrar/{id}', [\App\Http\Controllers\PerfilEstudanteController::class, 'rotina_monitoramento_aluno'])->name('rotina.cadastrar.aluno');
Route::prefix('rotina_monitoramento')->group(function () {
    // Rota para página inicial de rotina e monitoramento
    Route::get('/rot_monit_inicial', [RotinaCadastroController::class, 'rotina_monitoramento_inicial'])->name('rotina.monitoramento.inicial');
});




//minha alteracao
Route::get('/inicial', [SondagemInicialController::class, 'inicial'])->name('sondagem.inicial');
Route::get('/modalidade-ensino/inicial', [EnsinoController::class, 'inicial'])->name('modalidade.inicial');
// Rota de perfil movida para o grupo de Sondagem.
// Rota para exportar Excel
Route::post('/export/excel', [ExportExcelController::class, 'export'])->name('export.excel');
// Rota para gerar PDF
Route::post('/gerar/pdf', [GeneratePDFController::class, 'generatePDF'])->name('gerar.pdf');


// criando uma rota para acessar foccus-xampp
Route::get('/foccus-xampp', function () {
    return redirect()->away('http://localhost/proj_foccus/index.php');
})->name('foccus.xampp');



// Rota para o órgão
Route::get('/orgao', [OrgaoController::class, 'index'])->name('orgao');
// Rota para a Instituição
Route::get('/instituicao', [InstituicaoController::class, 'index'])->name('instituicao');

// Rota para a Escola
Route::get('/escola', [EscolaController::class, 'index'])->name('escola');

// Rota para o Aluno
Route::get('/alunos', [AlunosController::class, 'index'])->name('alunos');

// Rota para impressão de alunos (corrige erro de rota nas views)
Route::get('/imprime-aluno', [ImprimeAlunoController::class, 'imprimeAluno'])->name('imprime_aluno');

// Rota para o download
Route::get('/download', [downloadController::class, 'index'])->name('download');


use App\Http\Controllers\SomeController;  // Certifique-se de incluir o controlador

// Rota para "Como Eu Sou"
Route::get('/como-eu-sou', [SomeController::class, 'comoEuSou'])->name('como-eu-sou');

// Rota para "Emociômetro"
Route::get('/emociometro', [SomeController::class, 'emociometro'])->name('emociometro');

// Rota para "Minha Rede de Ajuda"
Route::get('/minha-rede-de-ajuda', [SomeController::class, 'minhaRedeDeAjuda'])->name('minha-rede-de-ajuda');

// Rotas para Monitoramento de Atividades
Route::prefix('monitoramento')->group(function () {
    // Rota para salvar os dados do monitoramento
    Route::post('/salvar', [MonitoramentoAtividadeController::class, 'salvar'])->name('monitoramento.salvar');
    // Rota para carregar os dados salvos do monitoramento
    Route::get('/carregar/{alunoId}', [MonitoramentoAtividadeController::class, 'carregar'])->name('monitoramento.carregar');
});

// Rota para gerar PDF
Route::post('/gerar-pdf', [GeneratePDFController::class, 'generatePDF'])->name('gerar.pdf');
// Rotas comentadas porque DocumentController não existe e está causando erro
// Route::post('/download-word', [DocumentController::class, 'generateWordExcel'])->name('download.word');
// Route::post('/download-excel', [DocumentController::class, 'downloadExcel'])->name('download.excel');
// Route::post('/download-pdf', [DocumentController::class, 'downloadPDF'])->name('download.pdf');


Route::get('/teste-email', function () {
    \Mail::raw('Teste de email do Laravel', function($message) {
        $message->to('marcosbarroso.info@gmail.com')->subject('Teste');
    });
    return 'Email enviado!';
});