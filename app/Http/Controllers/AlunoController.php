<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlunoController extends Controller
{
    protected $Perguntas_eixo_comunicacao;
    protected $perguntas_eixo_comportamento;
    protected $eixo_int_socio_emocional;

    public function __construct()
    {
        $this->Perguntas_eixo_comunicacao = [
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

        $this->perguntas_eixo_comportamento = [
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

        $this->eixo_int_socio_emocional = [
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
    }

    public function index($id)
    {
        $alunosDetalhados = Aluno::getAlunosDetalhados($id);
        if (!empty($alunosDetalhados)) {
            $aluno = $alunosDetalhados[0];
        } else {
            abort(404);
        }
        return view('alunos.perfil_estudante', compact('aluno'));
    }

    public function mostra_aluno_inventario($id, Request $request)
{
    // Obtém a fase da requisição, padrão para 'inicial' se não especificada
    $fase = $request->query('fase', 'inicial');
    
    // Busca dados detalhados (campos extras)
    $alunoDetalhado = Aluno::getAlunosDetalhados($id)[0] ?? abort(404);

    // Busca relacionamentos via Eloquent
    $aluno = Aluno::with([
        'eixoComunicacao',
        'eixoComportamento', 
        'eixoSocioEmocional',
        'preenchimento'
    ])->findOrFail($id);

    // Usa o array completo de perguntas do eixo comportamento
    $perguntas_eixo_comportamento_filtrado = $this->perguntas_eixo_comportamento;
    
    // Log para depuração
    Log::info('Perguntas do eixo comportamento:', $perguntas_eixo_comportamento_filtrado);
    Log::info('Total de perguntas do eixo comportamento:', ['total' => count($perguntas_eixo_comportamento_filtrado)]);
    Log::info('Fase da sondagem:', ['fase' => $fase]);
    
    return view('sondagem.inventarios', [
        'aluno' => $aluno, // Objeto Eloquent com relacionamentos
        'alunoDetalhado' => $alunoDetalhado, // Dados da query customizada
        'Perguntas_eixo_comunicacao' => $this->Perguntas_eixo_comunicacao,
        'perguntas_eixo_comportamento' => $perguntas_eixo_comportamento_filtrado,
        'eixo_int_socio_emocional' => $this->eixo_int_socio_emocional,
        'fase' => $fase // Passa a fase para a view
    ]);
}

    
    public function visualiza_aluno_inventario($id)
{
    $alunoDetalhado = Aluno::getAlunosDetalhados($id)[0] ?? abort(404);

    // Busca relacionamentos via Eloquent
    $aluno = Aluno::with([
        'eixoComunicacao',
        'eixoComportamento', 
        'eixoSocioEmocional',
        'preenchimento'
    ])->findOrFail($id);

        return view('sondagem.visualizar_inventario', [
            'aluno' => $aluno, // Objeto Aluno com todos os relacionamentos
          'alunoDetalhado' => $alunoDetalhado,
            'eixoComunicacao' => $aluno->eixoComunicacao, // Dados da tabela eixo_comunicacao_linguagem
            'eixoComportamento' => $aluno->eixoComportamento, // Dados da tabela eixo_comportamento
            'eixoSocioEmocional' => $aluno->eixoSocioEmocional, // Dados da tabela eixo_interacao_soc_emocional
            'preenchimento' => $aluno->preenchimento, // Dados da tabela preenchimento_inventario
            // Arrays com textos das perguntas
            'Perguntas_eixo_comunicacao' => $this->Perguntas_eixo_comunicacao,
            'perguntas_eixo_comportamento' => $this->perguntas_eixo_comportamento,
            'eixo_int_socio_emocional' => $this->eixo_int_socio_emocional
        ]);  
      }

    
}
