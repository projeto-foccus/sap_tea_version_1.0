<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Rotina e Monitoramento de Atividades</title>
  <style>
    
    .button-group {
    display: flex;
    gap: 10px; /* Espaçamento entre os botões */
    justify-content: center; /* Centraliza os botões */
    margin-top: 20px;
}

.btn {
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease-in-out;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #a71d2a;
}

.pdf-button {
    background-color: #28a745;
    color: white;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.pdf-button:hover {
    background-color: #1e7e34;
}
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    .container {
      border: 2px solid red;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: red;
    }

    .header-logos {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-logos img {
      width: 100px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 20px;
    }

    label {
      font-weight: bold;
    }

    input[type="text"],
    input[type="date"],
    select {
      width: 100%;
      padding: 5px;
    }

    .full-width {
      grid-column: span 2;
    }

    .alert-box {
      border: 1px solid gray;
      background-color: #f9f9f9;
      padding: 10px;
      margin-top: 20px;
      font-size: 14px;
    }

    .alert-box em {
      color: red;
      font-style: italic;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 5px;
      text-align: center;
    }

    th {
      background-color: #eee;
    }

    .retirar {
      color: red;
      font-weight: bold;
    }

    .obs-finais {
      margin-top: 20px;
    }

    .obs-finais textarea {
      width: 100%;
      height: 100px;
      padding: 5px;
    }
  </style>
</head>
<body>

  <div class="header-logos">
    <img src="logo-educacao.png" alt="Logo Educação">
    <h1>ROTINA E MONITORAMENTO DE <br> APLICAÇÃO DE ATIVIDADES 1 - INICIAL</h1>
    <img src="logo-focus.png" alt="Logo Focus">
  </div>

  <div class="container">
    <div class="form-grid">
      <div class="full-width">
        <label>Seleção do Aluno:</label>
        <select>
          <option value="">-- Selecione o aluno --</option>
          <option>Aluno 1</option>
          <option>Aluno 2</option>
          <option>Aluno 3</option>
        </select>
      </div>
      <div>
        <label>Secretaria de Educação do Município:</label>
        <input type="text">
      </div>
      <div>
        <label>Escola:</label>
        <input type="text">
      </div>
      <div>
        <label>Nome do Aluno:</label>
        <input type="text">
      </div>
      <div>
        <label>Data de Nascimento:</label>
        <input type="text" placeholder="//">
      </div>
      <div>
        <label>Idade:</label>
        <input type="text">
      </div>
      <div>
        <label>Ano/Série:</label>
        <input type="text">
      </div>
      <div>
        <label>Turma:</label>
        <input type="text">
      </div>
      <div>
        <label>RA:</label>
        <input type="text">
      </div>
      <div>
        <label>Período de Aplicação (Inicial):</label>
        <input type="date">
      </div>
    </div>

    <div class="alert-box">
      <strong>Caro(a) educador(a),</strong><br>
      Por favor, registre as atividades nas datas mencionadas e realize a devida anotação no quadro. Se necessário, utilize este espaço para marcar a aplicação e observações pertinentes. Após finalizar o processo, você deverá registrar no Suporte <strong>TEG Digital</strong> o cenário atual do aluno.<br>
      <em>Observação: Em caso de dúvidas, consulte o suporte técnico ou administrativo para orientação. TEA</em>
    </div>

    <h3 class="retirar">Atividades Realizadas — Retirar</h3>

    <table>
      <thead>
        <tr>
          <th>Atividade</th>
          <th>Data (Inicial)</th>
          <th>Realizou a Atividade?</th>
          <th>Com Apoio?</th>
          <th>Observações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>ECM01 - A mágica da gentileza</td>
          <td><input type="text" placeholder="//"></td>
          <td>
            <label>Sim <input type="checkbox"></label>
            <label>Não <input type="checkbox"></label>
          </td>
          <td>
            <label>Sim <input type="checkbox"></label>
            <label>Não <input type="checkbox"></label>
          </td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td>ECM02 - A mágica do brincar</td>
          <td><input type="text" placeholder="//"></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td>ECM03 - A mágica de compartilhar</td>
          <td><input type="text" placeholder="//"></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td>ECM04 - A mágica do cuidar</td>
          <td><input type="text" placeholder="//"></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td>ECM05 - A mágica do aprender</td>
          <td><input type="text" placeholder="//"></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td>ECM06 - Expressão lúdica</td>
          <td><input type="text" placeholder="//"></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><label>Sim <input type="checkbox"></label> <label>Não <input type="checkbox"></label></td>
          <td><input type="text"></td>
        </tr>
      </tbody>
    </table>

    <div class="obs-finais">
      <label>Observações Finais:</label><br>
      <textarea></textarea>
    </div>
  </div>
</body>
</html>



    <div class="button-group">
        
        <a href="{{ route('index') }}" class="btn btn-primary">Salvar</a>
    <a href="{{ route('index') }}" class="btn btn-danger">Cancelar</a>
        <button type="button" class="pdf-button">Gerar PDF</button>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<script>
      document.querySelector(".pdf-button").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;

    // Seleciona a parte da página que será capturada
    const element = document.body;

    // Usa html2canvas para converter a página em imagem
    html2canvas(element, { scale: 1.0 }).then(canvas => { // Reduzindo a escala para diminuir o tamanho
        const imgData = canvas.toDataURL("image/jpeg", 0.8); // Compressão JPEG (0.6 de qualidade)

        const pdf = new jsPDF("p", "mm", "a4"); // Cria um documento PDF

        // Ajusta a imagem no PDF
        const imgWidth = 210; // Largura A4 em mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width; // Mantém proporção

        pdf.addImage(imgData, "JPEG", 0, 0, imgWidth, imgHeight);
        pdf.save("Rotina_Monitoramento.pdf"); // Baixa o PDF
    });
});
</script>
</body>
</html>