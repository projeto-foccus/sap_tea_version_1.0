<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Aluno; // Importa o modelo Aluno
use Carbon\Carbon; // Para manipulação de datas
use App\Models\PerfilEstudante;
use App\Http\Controllers\MonitoramentoAtividadeController;

 

class PerfilEstudanteIndependenteController extends Controller
{

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
    $ano = date('Y');

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

    // Verificar status das fases usando controle_fases_sondagem
    $fasesStatus = [];
    foreach ($alunos as $aluno) {
        $controle = DB::table('controle_fases_sondagem')
            ->where('id_aluno', $aluno->alu_id)
            ->where('ano', $ano)
            ->first();
            
        if (!$controle) {
            DB::table('controle_fases_sondagem')->insert([
                'id_aluno' => $aluno->alu_id,
                'ano' => $ano,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $fasesStatus[$aluno->alu_id] = [
                'inicial' => false,
                'continuada1' => false,
                'continuada2' => false,
                'final' => false
            ];
        } else {
            $fasesStatus[$aluno->alu_id] = [
                'inicial' => $controle->fase_inicial === 'In',
                'continuada1' => $controle->fase_cont1 === 'Cont1',
                'continuada2' => $controle->fase_cont2 === 'Cont2',
                'final' => $controle->fase_final === 'Final'
            ];
        }
    }

    $faseHabilitada = in_array($fase, ['inicial', 'continuada1', 'continuada2', 'continuada3', 'final']);

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

