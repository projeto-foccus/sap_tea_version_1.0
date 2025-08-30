<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Download de Materiais</title>
  <!-- Ícones do FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #fdfbfb, #ebedee);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: white;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      width: 100%;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    h1 {
      font-size: 24px;
      margin-bottom: 25px;
      color: #333;
    }

    select {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 20px;
      background-color: #f9f9f9;
    }

    button {
      padding: 12px 30px;
      background-color: #007bff;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    #link-container {
      margin-top: 25px;
      font-size: 16px;
    }

    a.download-link {
      color: #28a745;
      font-weight: 600;
      text-decoration: none;
    }

    a.download-link:hover {
      text-decoration: underline;
    }

    .icon {
      font-size: 40px;
      color: #007bff;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="icon"><i class="fas fa-download"></i></div>
    <h1>Download de Materiais</h1>

    <select id="material-select">
      <option value="" disabled selected>Selecione um material</option>
      <option value="material1">📘 EMOCIONOMETRO</option>
      <option value="material2">🎨 EU COMO SOU</option>
      <option value="material3">📟 MINHA REDE DE AJUDA</option>
    </select>

    <button onclick="gerarLink()">
      <i class="fas fa-link"></i> Gerar Link
    </button>

    <div id="link-container"></div>
  </div>

  <script>
    function gerarLink() {
      const material = document.getElementById("material-select").value;
      const linkContainer = document.getElementById("link-container");
      let link = "";

      switch (material) {
        case "material1":
          link = "https://drive.google.com/file/d/1FiI3fWg1fKVAaV40uoKVZFdx1nLU2dmJ/view?usp=sharing";
          break;
        case "material2":
          link = "https://drive.google.com/your-css-guia-link";
          break;
        case "material3":
          link = "https://drive.google.com/your-js-manual-link";
          break;
        default:
          link = "";
      }

      if (link) {
        linkContainer.innerHTML = `
          <p><i class="fas fa-check-circle" style="color: green;"></i> Link gerado com sucesso:</p>
          <a href="${link}" target="_blank" class="download-link">👉 Baixar Material</a>
        `;
      } else {
        linkContainer.innerHTML = "<p style='color: red;'>Por favor, selecione um material.</p>";
      }
    }
  </script>
</body>
</html>
