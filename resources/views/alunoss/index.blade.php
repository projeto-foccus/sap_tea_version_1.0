<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        
        .formulario {
            max-width: 10000px;
            margin: 40px 0 0 20px; /* Ajustei aqui */
            padding: 20px;
            background-color: #fff;
            
           
        }
        
        .elemento {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
        }
        
        .inputgeral {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .submitbtn, .cancelbtn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        a.btn.btn-secondary {
  display: inline-block;
  background-color: #007bff; /* azul Bootstrap */
  color: white !important;
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  border: none;
  font-size: 16px;
  transition: background-color 0.3s, transform 0.1s;
}

a.btn.btn-secondary:hover {
  background-color: #0056b3;
  transform: scale(1.02); /* leve efeito ao passar o mouse */
}

.submitbtn {
  background-color: #28a745; /* verde Bootstrap */
  color: white;
  padding: 10px 20px; /* igual ao outro botão */
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.1s;
}

.submitbtn:hover {
  background-color: #218838;
  transform: scale(1.02);
}

    
.listarbtn {
  display: inline-block;
  background-color: #fd7e14; /* laranja Bootstrap */
  color: white !important;
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 16px;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.1s;
}

.listarbtn:hover {
  background-color: #e8590c;
  transform: scale(1.02);
}

        
        
       
        
     
        
        
    </style>


    <div id="formulario-cad-aluno" class="formulario">
        <form action="/proj_foccus/views/forms/incluir_aluno.php" method="POST">
            <h2>Cadastro de Aluno</h2>
            <section>

                <div class="elemento">
                    <label for="ra_alu">Registro do Aluno <br></br></label>
                    <input class="inputgeral" style="width: 20%;" type="text" name="ra_alu" id="ra_alu" placeholder="RA do aluno" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="nome_alu">Nome do aluno<br></br></label>
                    <input class="inputgeral" style="width: 100%;" type="text" name="nome_alu" id="nome_alu" placeholder="Digite o seu nome" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="dtnasc_alu">Data de Nascimento<br></br></label>
                    <input class="inputgeral" style="width: 150px;" type="date" name="dtnasc_alu" id="dtnasc_alu" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="inep_alu">Inep da escola<br></br></label>
                    <input class="inputgeral" style="width: 80px;" type="text" name="inep_alu" id="inep_alu" placeholder="INEP" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="resp_alu">Nome do responsável<br></br></label>
                    <input class="inputgeral" style="width: 100%;" type="text" name="resp_alu" id="resp_alu" placeholder="Digite o nome do seu responsável" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="tiporesp_alu">Tipo de parentesco<br></br></label>
                    <input class="inputgeral" style="width: 100%;" type="text" name="tiporesp_alu" id="tiporesp_alu" placeholder="Tipo de parentesco" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="email_resp">Email do responsável<br></br></label>
                    <input class="inputgeral" style="width: 100%;" type="email" name="email_resp" id="email_resp" placeholder="Digite o email do seu responsável" autocomplete="off" required>
                </div>

                <div class="elemento">
                    <label for="telefone_resp">Telefone do responsável<br></br></label>
                    <input class="inputgeral" style="width: 100%;" type="text" name="telefone_resp" id="telefone_resp" placeholder="(XX) XXXXX-XXXX" autocomplete="off" required>
                </div>

            </section>

            <div class="di">
                <button class="submitbtn" type="submit" name="submit">Enviar</button>
                <a href="{{ route('index') }}" class="btn btn-secondary mt-3">Voltar</a>
                <a class="listarbtn" id="listarbtn" data-url="controller/imprime_aluno.php">Listar</a>
                <div id="lista-container"></div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function applyMask(inputId, maskFunction) {
                let input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener("input", function () {
                        input.value = maskFunction(input.value);
                    });
                }
            }

            function maskTelefone(value) {
                return value
                    .replace(/\D/g, "") // Remove tudo que não for número
                    .replace(/^(\d{2})(\d)/, "($1) $2") // Coloca parênteses no DDD
                    .replace(/(\d{4,5})(\d{4})$/, "$1-$2"); // Formata o número
            }

            function maskInep(value) {
                return value.replace(/\D/g, "").substring(0, 8); // Apenas números (8 dígitos)
            }

            applyMask("telefone_resp", maskTelefone);
            applyMask("inep_alu", maskInep);
        });
    </script>


