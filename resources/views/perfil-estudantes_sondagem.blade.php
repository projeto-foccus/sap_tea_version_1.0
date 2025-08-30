@extends('index')

@section('styles')
<style>
        .perfil-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            position: relative;
            border-left: 10px solid rgba(0, 0, 0, 0.5); /* Amarelo apagado */
            padding-left: 15px; /* Ajuste para não colar no conteúdo */
        }
        h2 {
            text-align: center;
            color: #d35400;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }
        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .button-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 20px;
}

button {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    opacity: 0.8;
}

.cancel-button {
    background-color:rgb(230, 0, 0);
    color: white;
}

.pdf-button {
    background-color: #0073e6;
    color: white;
}

        input, select, textarea {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .comunicacao-section {
    border-left: 10px solid #003366; /* Azul escuro */
    padding-left: 15px; /* Para afastar o texto da borda */
}


    </style>
@endsection

@section('content')
    <div class="perfil-container">
        
        <h2>I - Perfil do Estudante</h2>
        <form method="POST" action="{{ route('perfil.estudante.salvar') }}">
            @csrf
            <div class="form-group">
                <label>Nome do estudante:</label>
                <input type="text" name="nome_aluno"  
                value="{{ $aluno->alu_nome }}" class="form-control">
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label>Ano/Série:</label>
                    <input type="text">
                </div>
                <div class="form-group">
              <label>Data de Cadastro:</label>
              <input type="text" name="alu_nasc"
               value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->format('d/m/Y') }}" 
               class="form-control">

               </div>


               <div class="form-group">
                <label>Idade:</label>
                <input type="text" name="idade" value="{{ $idade }} anos"
                 class="form-control" readonly>

                </div>

            </div>
            
            <div class="form-group">
                <label>Nome do Professor:</label>
                <input type="text" disabled placeholder="Dados importados do sistema">
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label>Possui diagnóstico/laudo?</label>
                    <select>
                        <option value = "1">Sim </option>
                        <option value = "0">Não</option>
                </select>
                </div>
                <div class="form-group">
                    <label>CID:</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Médico:</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Data do Laudo:</label>
                    <input type="date">
                </div>
                





                <div class="form-group">
                    <label>Nível suporteeeee</label>
                    <select>
                        <option value = "Nível 1 - Exige pouco apoio">Sim </option>
                        <option value = "Nível 2 - Exige apoio substancial ">Não</option>
                        <option value = "Nível 3 - Exige apoio muito substancial">Não</option>
                </select>

                <div class="form-group">
                    <label>Possui diagnóstico/laudo?</label>
                    <select>
                        <option value = "1">Sim </option>
                        <option value = "0">Não</option>
                </select>
                
            <div class="row">
                <div class="form-group">
                    <label>Possui diagnóstico/laudo?</label>
                    <select>
                        <option value = "1">Sim </option>
                        <option value = "0">Não</option>
                </select>
                </div>
                <div class="form-group">
                    <label>Faz uso de medicamento?</label>
                    <select><option>Sim</option><option>Não</option></select>
                </div>
                <div class="form-group">
                    <label>Quais?</label>
                    <input type="text">
                </div>
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label>Necessita de profissional de apoio em sala?</label>
                    <select><option>Sim</option><option>Não</option></select>
                </div>
                <div class="form-group">
                    <label>O estudante conta com profissional de apoio?</label>
                    <select>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Em quais momentos da rotina esse profissional se faz necessário?</label>
                <div class="checkbox-group">
                    <input value ="1" type="checkbox" id="locomocao"><label for="locomocao">Locomoção</label>
                    <input value ="2" type="checkbox" id="higiene"><label for="higiene">Higiene</label>
                    <input value ="3"type="checkbox" id="alimentacao"><label for="alimentacao">Alimentação</label>
                    <input value ="4" type="checkbox" id="comunicacao"><label for="comunicacao">Comunicação</label>
                    <input value ="5" type="checkbox" id="outros"><label for="outros">Outros momentos</label>
                </div>
                <input type="text" placeholder="Quais?">
            </div>
            
            <div class="form-group">
                <label>O estudante conta com Atendimento Educacional Especializado?</label>
                <select>
                    <option value = "1">Sim</option>
                    <option value = "0" >Não</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nome do profissional do AEE:</label>
                <input type="text">
            </div>
            
            <h2>II - Particularidades da sua personalidade</h2>
            
            <div class="form-group">
                <label>Principais características:</label>
                <textarea rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Principais áreas de interesse (brinquedos, jogos, temas, etc.):</label>
                <textarea rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Gosta de fazer no tempo livre:</label>
                <textarea rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Deixa o estudante muito feliz:</label>
                <textarea rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Deixa o estudante muito triste ou desconfortável:</label>
                <textarea rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Objeto de apego? Qual?</label>
                <textarea rows="3"></textarea>
            </div>


            <h2 class="comunicacao-section">III - Comunicação</h2>


            <div class="form-group">
                <label>Precisa de comunicação alternativa para expressar-se?</label>
                <select>
                    <option value = "1">Sim</option>
                    <option value = "0">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label>Entende instruções? dadas de forma verbal ?</label>
                <select>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label>Caso não, qual? Como você recomenda dar instruções?</label>
                <textarea rows="3"></textarea>
            </div>


            <h2>IV - Preferencias, sensibilidade e dificuldades</h2>
    
            <div class="form-group">
                <label>Apresenta sensibilidade:</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="s_auditiva"><label for="s_auditiva">Auditiva</label>
                    <input type="checkbox" id="s_visual"><label for="s_visual">Visual</label>
                    <input type="checkbox" id="s_tatil"><label for="s_tatil">Tátil</label>
                    <input type="checkbox" id="s_outros"><label for="s_outros">Outros estímulos</label>
                </div>
                
            </div>
            <div class="form-group">
                <label>Caso sim, qual? Como manejar em sala de aula</label>
                <textarea rows="3"></textarea>
            </div>
           
            <div class="form-group">
                <label>Apresenta seletividade alimentar?</label>
                <select>
                    <option>Sim</option>
                    <option>Não</option>
                </select>

            </div>
            <div class="form-group">
                <label>Alimentos preferidos:</label>
                <textarea rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Alimentos que evita:</label>
                <textarea rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Com quem tem mais afinidade na escola (professores,colegas)?Identifique</label>
                <textarea rows="3"></textarea>
            </div>
              <div class="form-group">
                <label>Como reage no contato com novas pessoas ou situaçõess</label>
                <textarea rows="3"></textarea>
            </div>
            <div class="form-group">
          <label>O que ajuda e o que dificulta sua interação na escola?</label>
          <textarea rows="3"></textarea>
      </div>

      <div class="form-group">
          <label>Há interesses específicos ou hiperfoco em algum tema ou atividade ?</label>
          <textarea rows="3"></textarea>
      </div>

      <div class="form-group">
                <label>Como ele(a) aprende melhor ?</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="r_visual"><label for="r_visual">Recurso visual</label>
                    <input type="checkbox" id="r_auditivo"><label for="r_auditivo">Recurso auditivo</label>
                    <input type="checkbox" id="m_concreto"><label for="m_concreto">Material concreto</label>
                    <input type="checkbox" id="o_outro"><label for="o_outro">Outro - identificar</label>
                </div>

                <div class="form-group">
          <label></label>
          <textarea rows="3"></textarea>
      </div>
      <div class="form-group">
          <label>Gosta de atividades em grupo ou prefere trabalhar sozinho ?</label>
          <textarea rows="3"></textarea>
      </div>
      <div class="form-group">
          <label>Quais estratégias são utilizadas e se mostram eficazes?</label>
          <textarea rows="3"></textarea>
      </div>
      <div class="form-group">
          <label>O que desperta seu interesse para realizar uma tarefa/atividades</label>
          <textarea rows="3"></textarea>
      </div>


      <h2 class="comunicacao-section">V - Informações adicionais da família</h2>
            
      <div class="form-group">
          <label>Há expectativas expressas da família em relação ao desempenho e a inclusão do estudante na sala de aula?</label>
          <textarea rows="3"></textarea>
      </div>
      <div class="form-group">
          <label>Existe alguma estatégia utilizada no contexto familiar que pode ser reaplicada na escola ?</label>
          <textarea rows="3"></textarea>
      </div>

      <div class="form-group">
          <label>Caso o estudante tenha uma crise ou situação de estresse elevado, o que fazer ?</label>
          <textarea rows="3"></textarea>
      </div>

    <div class="button-group">
        
    <button type="submit" formaction="/proj_foccus/index.php">Salvars</button>
<button type="button" class="cancel-button" onclick="window.location.href='/proj_foccus/index.blade.php'">Cancelar</button>
<a href="{{ route('index') }}" class="btn btn-danger">Cancelassr</a>
    <button type="button" class="pdf-button">Gerar PDF</button>
</div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<script>
    document.querySelector(".pdf-button").addEventListener("click", function () {
        const { jsPDF } = window.jspdf;

        // Seleciona a parte da página que será capturada
        const element = document.body; 

        // Usa html2canvas para converter a página em imagem
        html2canvas(element, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL("image/png"); // Converte para imagem
            const pdf = new jsPDF("p", "mm", "a4"); // Cria um documento PDF

            // Ajusta a imagem no PDF
            const imgWidth = 210; // Largura em mm (A4)
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
            pdf.save("perfil_estudante.pdf"); // Baixa o PDF
        });
    });
</script>
@endsection

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif