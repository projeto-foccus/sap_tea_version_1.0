<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Aluno; // Importa o modelo Aluno
use Carbon\Carbon; // Para manipulação de datas
use App\Models\PerfilEstudante;
use App\Http\Controllers\MonitoramentoAtividadeController;

 

class PerfilEstudanteController extends Controller
{
    public function rotina_monitoramento_aluno($aluno_id)
    {
        
        $professor_logado = auth('funcionario')->user();
        $professor_id = $professor_logado ? $professor_logado->func_id : null;

        // Garante que o aluno pertence ao professor logado
        $aluno = \App\Models\Aluno::where('alu_id', $aluno_id)
            ->whereHas('matriculas.turma', function($q) use ($professor_id) {
                $q->where('fk_cod_func', $professor_id);
            })->first();
        if (!$aluno) {
            return back()->withErrors(['msg' => 'Aluno não pertence ao professor logado ou não existe.']);
        }

        // Garante que a view recebe o aluno_id correto, extraído da URL
        $alunoId = $aluno_id;

        // Garante que existe registro nas três tabelas-eixo
        $tem_eixo_com = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $aluno_id)->exists();
        $tem_eixo_int = \App\Models\EixoInteracaoSocEmocional::where('fk_alu_id_eintsoc', $aluno_id)->exists();
        $tem_eixo_comp = \App\Models\EixoComportamento::where('fk_alu_id_ecomp', $aluno_id)->exists();
        if (!($tem_eixo_com && $tem_eixo_int && $tem_eixo_comp)) {
            return back()->withErrors(['msg' => 'O aluno precisa ter registros em todos os eixos (Comunicação, Interação Socioemocional e Comportamento) para acessar esta rotina.']);
        }

        // --- POPULAR ESTOQUE DE ATIVIDADES CASO NÃO EXISTA ---
        Log::info('Tentando popular estoque para o aluno', ['aluno_id' => $aluno_id]);
        $existeEstoque = DB::table('estoque_atividades')->where('aluno_id', $aluno_id)->exists();
        if (!$existeEstoque) {
            // Comunicação/Linguagem
            $Perguntas_eixo_comunicacao = [
                'Amplia gradativamente seu vocabulário?',     
                'Amplia gradativamente sua comunicação social?',
                'Apresenta entonação vocal, com boa articulação e ritmo adequado?',
                'Ativa conhecimentos prévios em situações de novas aprendizagens?',
                'Categoriza diferentes elementos de acordo com critérios preestabelecidos?',
                'Compreende e utiliza comunicação alternativa para comunicar-se?',
                'Compreende que pode receber ajuda de pessoas conhecidas que estão ao seu redor?',
                'Comunica fatos, acontecimentos e ações de seu cotidiano de modo compreensível, ainda que não seja por meio da linguagem verbal?',
                'Comunica suas necessidades básicas (banheiro, água, comida, entre outros)?',
                'Entende expressões faciais em uma conversa?',            
                'Executa mais de um comando sequencialmente?',
                'Expressa-se com clareza e objetividade?',
                'Faz uso de expressões faciais para se comunicar?',
                'Faz uso de gestos para se comunicar?',
                'Identifica diferentes elementos, ampliando seu repertório?',
                'Identifica semelhanças e diferenças entre elementos?',
                'Inicia uma situação comunicativa?',
                'Mantem uma situação comunicativa?',
                'Nomeia as pessoas que fazem parte de sua rede de apoio?',
                'Nomeia diferentes elementos, ampliando seu vocabulário?',
                'Possui autonomia para se comunicar, mesmo em situações que geram conflito?',
                'Realiza pareamento de elementos idênticos?',
                'Reconhece e pareia elementos diferentes?',
                'Reconhece visualmente estímulos apresentados?',
                'Refere-se a si mesmo em primeira pessoa?',
                'Respeita turnos de fala?',
                'Responde ao ouvir seu nome?"',
                'Solicita ajuda de pessoas que estão ao seu redor, quando necessário?',
                'Utiliza linguagem não verbal para se comunicar?',
                'Utiliza linguagem verbal para se comunicar?',
                'Utiliza respostas simples para se comunicar?',
                'Utiliza vocabulário adequado, de acordo com seu nível de desenvolvimento?'
            ];
            foreach ($Perguntas_eixo_comunicacao as $i => $descricao) {
                DB::table('estoque_atividades')->updateOrInsert([
                    'aluno_id' => $aluno_id,
                    'eixo' => 'comunicacao',
                    'cod_atividade' => 'ECM' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                ], [
                    'descricao' => $descricao,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Comportamento
            $perguntas_eixo_comportamento = [
                'Adapta-se com flexibilidade a mudanças, em sua rotina (familiar, escolar e social)?',
                'Apresenta autonomia na realização das atividades propostas?',
                'Autorregula-se evitando comportamentos disruptivos em situações de desconforto?',
                'Compreende acontecimentos de sua rotina por meio de ilustrações?',
                'Compreende regras de convivência?',
                'Entende ações de autocuidado?',
                'Faz uso de movimentos corporais, como: apontar, movimentar a cabeça em sinal afirmativo/negativo, entre outros?',
                'Imita gestos, movimentos e segue comandos?',
                'Inicia e finaliza as atividades propostas diariamente?',
                'Interage nos momentos de jogos, lazer e demais atividades, respeitando as regras de convivência?',
                'Mantem a organização na sua rotina escolar?',
                'Permanace sentado por mais de dez minutos para a realização das atividades?',
                'Realiza ações motoras que envolvam movimento e equilíbrio?',
                'Realiza atividades com atenção e tolerância?',
                'Realiza, em sua rotina, ações de autocuidado com autonomia?',
                'Reconhece e identifica alimentos que lhe são oferecidos?',
                'Responde a comandos de ordem direta?'
            ];
            foreach ($perguntas_eixo_comportamento as $i => $descricao) {
                DB::table('estoque_atividades')->updateOrInsert([
                    'aluno_id' => $aluno_id,
                    'eixo' => 'comportamento',
                    'cod_atividade' => 'ECP' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                ], [
                    'descricao' => $descricao,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Socioemocional
            $eixo_int_socio_emocional = [
                'Compartilha brinquedos e brincadeiras?',
                'Compartilha interesses?',
                'Controla suas emoções? (Autorregula-se)',
                'Coopera em situações que envolvem interação?',
                'Demonstra e compartilha afeto?',
                'Demonstra interesse nas atividades propostas?',
                'Expressa suas emoções?',
                'Identifica/reconhece a emoção do outro?',
                'Identifica/reconhece suas emoções?',
                'Inicia e mantém interação em situações sociais?',
                'Interage com o(a) professor(a), seus colegas e outras pessoas de seu convívio escolar?',
                'Interage, fazendo contato visual?',
                'Reconhece e entende seus sentimentos, pensamentos e comportamentos?',
                'Relaciona-se, estabelecendo vínculos?',
                'Respeita regras em jogos e brincadeiras?',
                'Respeita regras sociais?',
                'Responde a interações?',
                'Solicita ajuda, quando necessário?'
            ];
            foreach ($eixo_int_socio_emocional as $i => $descricao) {
                DB::table('estoque_atividades')->updateOrInsert([
                    'aluno_id' => $aluno_id,
                    'eixo' => 'socioemocional',
                    'cod_atividade' => 'EIS' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                ], [
                    'descricao' => $descricao,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        Log::info('Finalizou tentativa de popular estoque para o aluno', ['aluno_id' => $aluno_id]);

        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($aluno_id);

        $eixoCom = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $aluno_id)
            ->where('fase_inv_com_lin', 'In')
            ->first();
        $data_inicial_com_lin = $eixoCom ? $eixoCom->data_insert_com_lin : null;
        $professor_nome = $professor_logado ? $professor_logado->func_nome : null;

        $comunicacao_resultados = \App\Models\ResultEixoComLin::with('proposta')->where('fk_result_alu_id_ecomling', $aluno_id)->get();
        $comunicacao_resultados = $comunicacao_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_com_lin;
        })->values();

        $comportamento_resultados = \App\Models\ResultEixoComportamento::with('proposta')->where('fk_result_alu_id_comportamento', $aluno_id)->get();
        $comportamento_resultados = $comportamento_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_comportamento;
        })->values();

        $socioemocional_resultados = \App\Models\ResultEixoIntSocio::with('proposta')->where('fk_result_alu_id_int_socio', $aluno_id)->get();
        $socioemocional_resultados = $socioemocional_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_int_soc;
        })->values();

        // DATA DE HOJE PARA FILTRO
        $hoje = date('Y-m-d');

        // Consultar quantas vezes cada código de atividade já foi registrado HOJE por eixo
        $comportamentoRegistrosHoje = DB::table('cad_ativ_eixo_comportamento')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $aluno_id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();

        $comunicacaoRegistrosHoje = DB::table('cad_ativ_eixo_com_lin')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $aluno_id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();

        $socioemocionalRegistrosHoje = DB::table('cad_ativ_eixo_int_socio')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $aluno_id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();

        // Buscar atividades do eixo comunicação/linguagem do aluno via JOIN
        $comunicacao_atividades = DB::table('atividade_com_lin as acom')
    ->join('result_eixo_com_lin as res', 'res.fk_id_pro_com_lin', '=', 'acom.id_ati_com_lin')
    ->where('res.fk_result_alu_id_ecomling', $aluno_id)
    ->select('acom.id_ati_com_lin', 'acom.cod_ati_com_lin', 'acom.desc_ati_com_lin')
    ->get();

        // Frequência e ordenação para comunicação
        $comunicacao_frequencias = $comunicacao_atividades->groupBy('cod_ati_com_lin')->map->count();
        $comunicacao_atividades_ordenadas = $comunicacao_atividades->sortByDesc(function($item) use ($comunicacao_frequencias) {
            return $comunicacao_frequencias[$item->cod_ati_com_lin];
        })->values();

        $comunicacao_atividades_assoc = [];
        foreach ($comunicacao_atividades as $a) {
            $comunicacao_atividades_assoc[$a->id_ati_com_lin] = [
                'codigo' => $a->cod_ati_com_lin,
                'descricao' => $a->desc_ati_com_lin
            ];
        }

        $comportamento_atividades = DB::table('atividade_comportamento as acom')
    ->join('result_eixo_comportamento as res', 'res.fk_id_pro_comportamento', '=', 'acom.id_ati_comportamento')
    ->where('res.fk_result_alu_id_comportamento', $aluno_id)
    ->select('acom.id_ati_comportamento', 'acom.cod_ati_comportamento', 'acom.desc_ati_comportamento')
    ->get();

        // Frequência e ordenação para comportamento
        $comportamento_frequencias = $comportamento_atividades->groupBy('cod_ati_comportamento')->map->count();
        $comportamento_atividades_ordenadas = $comportamento_atividades->sortByDesc(function($item) use ($comportamento_frequencias) {
            return $comportamento_frequencias[$item->cod_ati_comportamento];
        })->values();

        $comportamento_atividades_assoc = [];
        foreach ($comportamento_atividades as $a) {
            $comportamento_atividades_assoc[$a->id_ati_comportamento] = [
                'codigo' => $a->cod_ati_comportamento,
                'descricao' => $a->desc_ati_comportamento
            ];
        }

        $socioemocional_atividades = DB::table('atividade_int_soc as acom')
    ->join('result_eixo_int_socio as res', 'res.fk_id_pro_int_socio', '=', 'acom.id_ati_int_soc')
    ->where('res.fk_result_alu_id_int_socio', $aluno_id)
    ->select('acom.cod_ati_int_soc', 'acom.desc_ati_int_soc')
    ->get();

        // Frequência e ordenação para socioemocional
        $socioemocional_frequencias = $socioemocional_atividades->groupBy('cod_ati_int_soc')->map->count();
        $socioemocional_atividades_ordenadas = $socioemocional_atividades->sortByDesc(function($item) use ($socioemocional_frequencias) {
            return $socioemocional_frequencias[$item->cod_ati_int_soc];
        })->values();

        // Buscar propostas e indexar por id
        $comunicacao_propostas = \App\Models\PropostaComLin::all()->keyBy('id_pro_com_lin');
        $comportamento_propostas = \App\Models\PropostaComportamento::all()->keyBy('id_pro_comportamento');
        $socioemocional_propostas = \App\Models\PropostaIntSoc::all()->keyBy('id_pro_int_soc');

        // Consultas SQL já trazem as atividades certas por eixo, ordenadas por código
        // Já estão sendo feitas acima e armazenadas em $comunicacao_atividades, $comportamento_atividades, $socioemocional_atividades
        // DEBUG: Agrupamento dos três eixos em um único array
        $debug_atividades_agrupadas = [
            'comunicacao' => DB::table('result_eixo_com_lin')
    ->select(
        'fk_id_pro_com_lin as id_ati_com_lin',
        'fk_hab_pro_com_lin',
        DB::raw('count(*) as qtd'),
        DB::raw('GROUP_CONCAT(id_result_eixo_com_lin) as ids'),
        DB::raw('MAX(date_cadastro) as ultima_data')
    )
    ->where('fk_result_alu_id_ecomling', $aluno_id)
    ->groupBy('fk_id_pro_com_lin', 'fk_hab_pro_com_lin')
    ->get(),
            'comportamento' => DB::table('result_eixo_comportamento')
    ->select(
        'fk_id_pro_comportamento as id_ati_comportamento',
        'fk_hab_pro_comportamento',
        DB::raw('count(*) as qtd'),
        DB::raw('GROUP_CONCAT(id_result_eixo_comportamento) as ids'),
        DB::raw('MAX(date_cadastro) as ultima_data')
    )
    ->where('fk_result_alu_id_comportamento', $aluno_id)
    ->groupBy('fk_id_pro_comportamento', 'fk_hab_pro_comportamento')
    ->get(),
            'socioemocional' => DB::table('result_eixo_int_socio')
    ->select(
        'fk_id_pro_int_socio as id_ati_int_soc',
        'fk_hab_pro_int_socio',
        DB::raw('count(*) as qtd'),
        DB::raw('GROUP_CONCAT(id_result_eixo_int_socio) as ids'),
        DB::raw('MAX(date_cadastro) as ultima_data')
    )
    ->where('fk_result_alu_id_int_socio', $aluno_id)
    ->groupBy('fk_id_pro_int_socio', 'fk_hab_pro_int_socio')
    ->get(),
        ];

        // Calcula o total de todos os eixos (total_eixos)
        // Calcula o total de atividades dos três eixos (cada ocorrência em cada eixo)
        $total_eixos = 0;
        if (isset($comunicacao_frequencias) && is_iterable($comunicacao_frequencias)) {
            foreach ($comunicacao_frequencias as $qtd) {
                $total_eixos += (int)$qtd;
            }
        }
        if (isset($comportamento_frequencias) && is_iterable($comportamento_frequencias)) {
            foreach ($comportamento_frequencias as $qtd) {
                $total_eixos += (int)$qtd;
            }
        }
        if (isset($socioemocional_frequencias) && is_iterable($socioemocional_frequencias)) {
            foreach ($socioemocional_frequencias as $qtd) {
                $total_eixos += (int)$qtd;
            }
        }

// --- NOVO BLOCO: Carregar códigos já preenchidos por eixo na data de hoje ---
$hoje = date('Y-m-d');
$codigosPreenchidosCom = [];
$codigosPreenchidosComp = [];
$codigosPreenchidosSoc = [];

// Importa o controller de monitoramento
$monitoramentoController = app(MonitoramentoAtividadeController::class);
$dadosMonitoramento = $monitoramentoController->carregarParaView($aluno_id);

// Comunicação
if (!empty($dadosMonitoramento['comunicacao'])) {
    foreach ($dadosMonitoramento['comunicacao'] as $cod => $registros) {
        foreach ($registros as $registro) {
            if (!empty($registro['data_aplicacao']) && $registro['data_aplicacao'] == $hoje) {
                $codigosPreenchidosCom[] = $cod;
                break;
            }
        }
    }
}
// Comportamento
if (!empty($dadosMonitoramento['comportamento'])) {
    foreach ($dadosMonitoramento['comportamento'] as $cod => $registros) {
        foreach ($registros as $registro) {
            if (!empty($registro['data_aplicacao']) && $registro['data_aplicacao'] == $hoje) {
                $codigosPreenchidosComp[] = $cod;
                break;
            }
        }
    }
}
// Socioemocional
if (!empty($dadosMonitoramento['socioemocional'])) {
    foreach ($dadosMonitoramento['socioemocional'] as $cod => $registros) {
        foreach ($registros as $registro) {
            if (!empty($registro['data_aplicacao']) && $registro['data_aplicacao'] == $hoje) {
                $codigosPreenchidosSoc[] = $cod;
                break;
            }
        }
    }
}

return view('rotina_monitoramento.monitoramento_aluno', compact(
    'alunoDetalhado',
    'data_inicial_com_lin',
    'professor_nome',
    'comunicacao_atividades',
    'comunicacao_atividades_ordenadas',
    'comunicacao_atividades_assoc',
    'comportamento_atividades',
    'comportamento_atividades_ordenadas',
    'comportamento_atividades_assoc',
    'socioemocional_atividades',
    'socioemocional_atividades_ordenadas',
    'socioemocional_atividades_assoc',
    'debug_atividades_agrupadas',
    'total_eixos',
    'hoje',
    'codigosPreenchidosCom',
    'codigosPreenchidosComp',
    'codigosPreenchidosSoc',
    'dadosMonitoramento'
));
    }

    public function index()
{
    // Busca apenas alunos das turmas do professor logado
    $professor = auth('funcionario')->user();
    $funcId = $professor->func_id;
    $alunos = \App\Models\Aluno::porProfessor($funcId)
        ->orderBy('alu_nome', 'asc')
        ->get();

    return view('alunos.perfil_estudante_aluno', [
        'alunos' => $alunos,
        'titulo' => 'Alunos Matriculados',
        'rota_inventario' => 'perfil_estudante.index_inventario',
        'flag_teste' => true,
        'professor_nome' => $professor->func_nome ?? '',
    ]);
}

public function index_inventario(Request $request, $fase = 'inicial')
{
    $professor = auth('funcionario')->user();
    $funcId = $professor->func_id;

    $alunos = \App\Models\Aluno::porProfessor($funcId)
        ->orderBy('alu_nome', 'asc')
        ->get();

    // Títulos para cada fase
    $titulos = [
        'inicial' => 'Sondagem Inicial',
        'continuada1' => 'Sondagem 1ª Cont.',
        'continuada2' => 'Sondagem 2ª Cont.',
        'continuada3' => 'Sondagem 3ª Cont.',
        'final' => 'Sondagem Final'
    ];

    $titulo = $titulos[$fase] ?? 'Sondagem';

    // Verifica se a fase está habilitada
    $faseHabilitada = in_array($fase, ['inicial', 'continuada1', 'continuada2', 'continuada3', 'final']); // Todas as fases estão habilitadas

    // Teste: se vier do menu de rotina, mostre botões diferentes
    $contexto = $request->get('contexto');
    if ($contexto === 'rotina') {
        return view('alunos.imprime_aluno_eixo', [
            'alunos' => $alunos,
            'titulo' => 'Rotina de Monitoramento',
            'botoes' => [
                [
                    'label' => 'Cadastrar Rotina',
                    'rota'  => 'rotina.monitoramento.cadastrar',
                    'classe' => 'btn-success w-100'
                ]
            ],
            'professor_nome' => $professor->func_nome,
            'fase' => $fase
        ]);
    }

    // Default: inventário
    return view('alunos.imprime_aluno_eixo', [
        'alunos' => $alunos,
        'titulo' => $titulo,
        'rota_acao' => 'alunos.inventario',
        'rota_pdf' => 'visualizar.inventario',
        'exibeBotaoInventario' => $faseHabilitada,
        'exibeBotaoPdf' => true,
        'professor_nome' => $professor->func_nome,
        'fase' => $fase
    ]);
}

public function mostrar($id)
{
    // Busca o aluno com detalhes completos pelo ID no banco de dados
    $aluno = Aluno::findOrFail($id);
    $alunoDetalhado = Aluno::getAlunosDetalhados($id);
    
    
    // getAlunosDetalhados retorna uma coleção, mas precisamos do primeiro item
    $alunoInfo = !empty($alunoDetalhado) ? $alunoDetalhado[0] : null;

    // Calcula a idade com base na data de nascimento
    $idade = Carbon::parse($aluno->alu_dtnasc)->age;

    // Retorna a view com os dados do aluno e a idade
    return view('alunos.perfil_estudante', [
        'aluno' => $aluno,
        'alunoDetalhado' => $alunoInfo,
        'idade' => $idade
    ]);
}

public function mostra_aluno_eixo($id)
{
    $aluno = Aluno::findOrFail($id);
    $_eixo = Carbon::parse($aluno->alu_dtnasc)->age;
    //$alunos = Aluno::all(); // Busca todos os alunos no banco de dados

    return view('alunos.imprime_aluno_eixo', compact('alunos','idade_eixo'));
}
    public function rotina_monitoramento_inicial()
    {
        $professor = auth('funcionario')->user();
        $funcId = $professor->func_id;

        // Busca apenas alunos das turmas do professor logado
        $alunos = \App\Models\Aluno::porProfessor($funcId)
            ->orderBy('alu_nome', 'asc')
            ->get();

        return view('alunos.imprime_aluno_eixo', [
            'alunos' => $alunos,
            'titulo' => 'Rotina de Monitoramento',
            'botoes' => [
                [
                    'label' => 'Cadastrar Rotina',
                    'rota'  => 'rotina.monitoramento.cadastrar',
                    'classe' => 'btn-success w-100'
                ]
            ],
            'professor_nome' => $professor->func_nome,
        ]);
    }

    /**
     * Exibe a tela de cadastro de rotina para o aluno selecionado
     */
    public function cadastrar_rotina_aluno($id)
    {
        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($id);
        $professor = auth('funcionario')->user();
        if (!$alunoDetalhado) {
            return back()->withErrors(['msg' => 'Não foi possível carregar os dados do aluno. Por favor, acesse o formulário pela rota correta ou verifique se o aluno existe.']);
        }
        // Buscar data inicial do eixo comunicação linguagem, se necessário
        $eixoCom = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $id)
            ->where('fase_inv_com_lin', 'In')
            ->first();
        $data_inicial_com_lin = $eixoCom ? $eixoCom->data_insert_com_lin : null;

        // Buscar resultados dos três eixos
        $comunicacao_resultados = \App\Models\ResultEixoComLin::where('fk_id_pro_com_lin', $id)->paginate(20);
        $comportamento_resultados = \App\Models\ResultEixoComportamento::where('fk_result_alu_id_comportamento', $id)->paginate(20);
        $socioemocional_resultados = \App\Models\ResultEixoIntSocio::where('fk_result_alu_id_int_socio', $id)->paginate(20);

        // Buscar atividades do eixo comunicação/linguagem do aluno via JOIN
        $comunicacao_atividades = DB::table('atividade_com_lin as acom')
            ->join('result_eixo_com_lin as res', 'acom.id_ati_com_lin', '=', 'res.fk_id_pro_com_lin')
            ->where('res.fk_result_alu_id_ecomling', $id)
            ->select('acom.cod_ati_com_lin', 'acom.desc_ati_com_lin')
            ->get();
        $comunicacao_frequencias = $comunicacao_atividades->groupBy('cod_ati_com_lin')->map->count();
        $comunicacao_atividades_ordenadas = collect();
        foreach ($comunicacao_atividades as $item) {
            $freq = $comunicacao_frequencias[$item->cod_ati_com_lin];
            for ($i = 0; $i < $freq; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_com_lin = $item->cod_ati_com_lin;
                $obj->desc_ati_com_lin = $item->desc_ati_com_lin;
                $comunicacao_atividades_ordenadas->push($obj);
            }
            // Para não repetir além do necessário, zera a frequência
            $comunicacao_frequencias[$item->cod_ati_com_lin] = 0;
        }
        // Ordena por código, pode ajustar se quiser por frequência
        $comunicacao_atividades_ordenadas = $comunicacao_atividades_ordenadas->sortByDesc('cod_ati_com_lin')->values();
        // Buscar atividades do eixo comportamento do aluno via JOIN
        $comportamento_atividades = DB::table('atividade_comportamento as aco')
            ->join('result_eixo_comportamento as res', 'aco.id_ati_comportamento', '=', 'res.fk_id_pro_comportamento')
            ->where('res.fk_result_alu_id_comportamento', $id)
            ->select('aco.cod_ati_comportamento', 'aco.desc_ati_comportamento')
            ->get();
        $comportamento_frequencias = $comportamento_atividades->groupBy('cod_ati_comportamento')->map->count();
        $comportamento_atividades_ordenadas = collect();
        foreach ($comportamento_atividades as $item) {
            $freq = $comportamento_frequencias[$item->cod_ati_comportamento];
            for ($i = 0; $i < $freq; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_comportamento = $item->cod_ati_comportamento;
                $obj->desc_ati_comportamento = $item->desc_ati_comportamento;
                $comportamento_atividades_ordenadas->push($obj);
            }
            $comportamento_frequencias[$item->cod_ati_comportamento] = 0;
        }
        $comportamento_atividades_ordenadas = $comportamento_atividades_ordenadas->sortByDesc('cod_ati_comportamento')->values();
        // Buscar atividades do eixo interação socioemocional via JOIN
        $socioemocional_atividades = DB::table('atividade_int_soc as ais')
            ->join('result_eixo_int_socio as res', 'ais.id_ati_int_soc', '=', 'res.fk_id_pro_int_socio')
            ->where('res.fk_result_alu_id_int_socio', $id)
            ->select('ais.cod_ati_int_soc', 'ais.desc_ati_int_soc')
            ->get();
        $socioemocional_frequencias = $socioemocional_atividades->groupBy('cod_ati_int_soc')->map->count();
        $socioemocional_atividades_ordenadas = collect();
        foreach ($socioemocional_atividades as $item) {
            $freq = $socioemocional_frequencias[$item->cod_ati_int_soc];
            for ($i = 0; $i < $freq; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_int_soc = $item->cod_ati_int_soc;
                $obj->desc_ati_int_soc = $item->desc_ati_int_soc;
                $socioemocional_atividades_ordenadas->push($obj);
            }
        }
        $socioemocional_atividades_ordenadas = $socioemocional_atividades_ordenadas->sortByDesc('cod_ati_int_socio')->values();

        // Buscar propostas e indexar por id
        $comunicacao_propostas = \App\Models\PropostaComLin::all()->keyBy('id_pro_com_lin');
        $comportamento_propostas = \App\Models\PropostaComportamento::all()->keyBy('id_pro_comportamento');
        $socioemocional_propostas = \App\Models\PropostaIntSoc::all()->keyBy('id_pro_int_soc');

        // DATA DE HOJE PARA FILTRO
        $hoje = date('Y-m-d');
        // Consultar quantas vezes cada código de atividade já foi registrado HOJE por eixo
        $comportamentoRegistrosHoje = DB::table('cad_ativ_eixo_comportamento')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();
        $comunicacaoRegistrosHoje = DB::table('cad_ativ_eixo_com_lin')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();
        $socioemocionalRegistrosHoje = DB::table('cad_ativ_eixo_int_socio')
            ->select('cod_atividade', DB::raw('COUNT(*) as total'))
            ->where('aluno_id', $id)
            ->where('data_aplicacao', $hoje)
            ->groupBy('cod_atividade')
            ->pluck('total', 'cod_atividade')
            ->toArray();

        return view('rotina_monitoramento.monitoramento_aluno', [
    'alunoId' => $id, 
            'alunoDetalhado' => $alunoDetalhado,
            'professor_nome' => $professor->func_nome,
            'data_inicial_com_lin' => $data_inicial_com_lin,
            'comunicacao_resultados' => $comunicacao_resultados,
            
            
            
            'comunicacao_atividades' => $comunicacao_atividades,
            'comportamento_atividades' => $comportamento_atividades,
            'socioemocional_atividades' => $socioemocional_atividades,
            'comunicacao_atividades_ordenadas' => $comunicacao_atividades_ordenadas,
            'comunicacao_linguagem_agrupado' => DB::select("
    SELECT 
        r.fk_id_pro_com_lin,
        r.fk_result_alu_id_ecomling,
        r.tipo_fase_com_lin,
        a.cod_ati_com_lin,
        a.desc_ati_com_lin,
        COUNT(*) AS total
    FROM result_eixo_com_lin r
    JOIN atividade_com_lin a ON r.fk_id_pro_com_lin = a.id_ati_com_lin
    WHERE r.fk_result_alu_id_ecomling = ?
    GROUP BY r.fk_id_pro_com_lin, r.fk_result_alu_id_ecomling, r.tipo_fase_com_lin, a.cod_ati_com_lin, a.desc_ati_com_lin
    ORDER BY total DESC
", [$id]),
            'comportamento_agrupado' => DB::select("
    SELECT 
        r.fk_id_pro_comportamento,
        r.fk_result_alu_id_comportamento,
        r.tipo_fase_comportamento,
        a.cod_ati_comportamento,
        a.desc_ati_comportamento,
        COUNT(*) AS total
    FROM result_eixo_comportamento r
    JOIN atividade_comportamento a ON r.fk_id_pro_comportamento = a.id_ati_comportamento
    WHERE r.fk_result_alu_id_comportamento = ?
    GROUP BY r.fk_id_pro_comportamento, r.fk_result_alu_id_comportamento, r.tipo_fase_comportamento, a.cod_ati_comportamento, a.desc_ati_comportamento
    ORDER BY total DESC
", [$id]),
            'socioemocional_agrupado' => DB::select("
    SELECT 
        r.fk_id_pro_int_socio,
        r.fk_result_alu_id_int_socio,
        r.tipo_fase_int_socio,
        a.cod_ati_int_soc,
        a.desc_ati_int_soc,
        COUNT(*) AS total
    FROM result_eixo_int_socio r
    JOIN atividade_int_soc a ON r.fk_id_pro_int_socio = a.id_ati_int_soc
    WHERE r.fk_result_alu_id_int_socio = ?
    GROUP BY r.fk_id_pro_int_socio, r.fk_result_alu_id_int_socio, r.tipo_fase_int_socio, a.cod_ati_int_soc, a.desc_ati_int_soc
    ORDER BY total DESC
", [$id]),
            'comportamento_atividades_ordenadas' => $comportamento_atividades_ordenadas,
            'socioemocional_atividades_ordenadas' => $socioemocional_atividades_ordenadas,
            'comportamento_resultados' => $comportamento_resultados,
            'socioemocional_resultados' => $socioemocional_resultados,
            'comunicacao_propostas' => $comunicacao_propostas,
            'comportamento_propostas' => $comportamento_propostas,
            'socioemocional_propostas' => $socioemocional_propostas,
            'comportamentoRegistrosHoje' => $comportamentoRegistrosHoje,
            'comunicacaoRegistrosHoje' => $comunicacaoRegistrosHoje,
            'socioemocionalRegistrosHoje' => $socioemocionalRegistrosHoje,
            // Adicione outros dados necessários para a view
        ]);
    }

    /**
     * Salva a rotina de monitoramento do aluno
     */
    public function salvar_rotina(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:1000',
        ]);

        // Exemplo genérico de salvamento, ajuste conforme seu modelo real
        DB::table('rotinas')->insert([
            'aluno_id' => $id,
            'descricao' => $request->descricao,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('rotina.monitoramento.cadastrar', ['id' => $id])
            ->with('success', 'Rotina salva com sucesso!');
    }
    
    /**
     * Lista alunos para o Indicativo Inicial
     * Similar ao método rotina_monitoramento_inicial mas para o contexto de Indicativo Inicial
     */
    public function listarAlunosIndicativo()
    {
        $professor = auth('funcionario')->user();
        if (!$professor) {
            // Redireciona para login ou mostra erro amigável
            return redirect()->route('login')->withErrors(['msg' => 'Sessão expirada ou acesso não autorizado. Faça login novamente.']);
        }
        $funcId = $professor->func_id;

        $alunos = \App\Models\Aluno::porProfessor($funcId)
            ->whereHas('eixoComunicacao')
            ->whereHas('eixoSocioEmocional')
            ->whereHas('eixoComportamento')
            ->orderBy('alu_nome', 'asc')
            ->get();

        return view('alunos.imprime_aluno_eixo', [
            'alunos' => $alunos,
            'titulo' => 'Indicativo de Atividades - Inicial',
            'botoes' => [
                [
                    'label' => 'Indicativo Inicial',
                    'rota'  => 'indicativo.inicial',
                    'classe' => 'btn-primary'
                ]
            ],
            'professor_nome' => $professor->func_nome,
            'contexto' => 'indicativo_inicial'
        ]);
    }

    /**
     * Lista alunos para o Perfil Família
     * Similar ao método listarAlunosIndicativo mas para o contexto de Perfil Família
     */
    public function listarAlunosFamilia()
    {
        $professor = auth('funcionario')->user();
        if (!$professor) {
            return redirect()->route('login')->withErrors(['msg' => 'Sessão expirada ou acesso não autorizado. Faça login novamente.']);
        }
        $funcId = $professor->func_id;

        $alunos = \App\Models\Aluno::porProfessor($funcId)
            ->whereHas('eixoComunicacao')
            ->whereHas('eixoSocioEmocional')
            ->whereHas('eixoComportamento')
            ->orderBy('alu_nome', 'asc')
            ->get();

        return view('familia.lista_alunos', [
            'alunos' => $alunos,
            'titulo' => 'Perfil Família - Inicial',
            'botoes' => [
                [
                    'label' => 'Perfil Inicial',
                    'rota'  => 'familia.inicial',
                    'classe' => 'btn-primary'
                ]
            ],
            'professor_nome' => $professor->func_nome,
            'contexto' => 'familia_inicial'
        ]);
    }
}

