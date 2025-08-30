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
<div id="formulario-cad-orgao" class="formulario">
        <form action="/proj_foccus/views/forms/incluir_orgao.php" method="POST">
            <h2>Cadastro de Orgão</h2>
            <section>
                <div class="elemento">
                    <div>
                        <label> Razão Social<br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="desc_org" id="desc_org" placeholder='Digite o nome do orgão' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> CNPJ <br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="cnpj_org" id="cnpj_org" placeholder='__.___.___/___-__  ' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> Endereço<br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="endereco_org" id="endereco_org" placeholder='Digite seu endereço' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> Bairro<br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="bairro_org" id="bairro_org" placeholder='Digite seu bairro' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> Município<br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="municipio_org" id="municipio_org" placeholder='Digite seu bairro' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> CEP <br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="cep_org" id="cep_org" placeholder='*****-***' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> Estado<br></br></label>
                        <select name="uf_org" id="uf_org" class="selectgeral" style="width: 100%;" autoComplete='off' required>
                            <option value="PE">PE</option>
                            <option value="RO">RO</option>
                            <option value="AC">AC</option>
                            <option value="AM">AM</option>
                            <option value="RR">RR</option>
                            <option value="PA">PA</option>
                            <option value="AP">AP</option>
                            <option value="TO">TO</option>
                            <option value="MA">MA</option>
                            <option value="PI">PI</option>
                            <option value="CE">CE</option>
                            <option value="RN">RN</option>
                            <option value="PB">PB</option>
                            <option value="AL">AL</option>
                            <option value="SE">SE</option>
                            <option value="BA">BA</option>
                            <option value="MG">MG</option>
                            <option value="ES">ES</option>
                            <option value="RJ">RJ</option>
                            <option value="SP">SP</option>
                            <option value="PR">PR</option>
                            <option value="SC">SC</option>
                            <option value="RS">RS</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="GO">GO</option>
                            <option value="DF">DF</option>
                        </select>
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> E-mail<br></br></label>
                        <input class="inputgeral" style="width: 100%;" type='text' name="email_org" id="email_org" placeholder='Exemplo@email.com' autoComplete='off' required />
                    </div>
                </div>
                <div class="elemento">
                    <div>
                        <label> Telefone - orgão<br></br></label>
                        <input class="inputgeral" style="width: 100%;" name="telefone_org" id="telefone_org" type='text' placeholder='( * * ) * * * * * - * * * * ' autoComplete='off'  required />
                    </div>
                    </div>
            </section>
            <div class="di button-container">
                <button class="submitbtn" type="submit" name="submit">Salvar</button>
                
                <a href="{{ route('index') }}" class="btn btn-secondary mt-3">Voltar</a>
                <a class="listarbtn" id="listarbtn" data-url="controller/imprime_orgao.php">Listar</a>
                </div>
                <div id="lista-container"></div>
            </div>
        </form>
    </div>

    <script> document.addEventListener('DOMContentLoaded', () => {
    function aplicarMascara(input, mascara) {
        input.addEventListener('input', (e) => {
            let valor = e.target.value.replace(/\D/g, '');
            let resultado = '';
            let indexMascara = 0;

            for (let i = 0; i < valor.length; i++) {
                if (indexMascara >= mascara.length) break;

                if (mascara[indexMascara] === 'X') {
                    resultado += valor[i];
                    indexMascara++;
                } else {
                    resultado += mascara[indexMascara];
                    indexMascara++;
                    i--;
                }
            }

            e.target.value = resultado;
        });
    }

    // Máscaras
    const cnpjInput = document.getElementById('cnpj_org');
    if (cnpjInput) aplicarMascara(cnpjInput, 'XX.XXX.XXX/XXXX-XX');

    const cepInput = document.getElementById('cep_org');
    if (cepInput) aplicarMascara(cepInput, 'XXXXX-XXX');

    const telefoneInput = document.getElementById('telefone_org');
    if (telefoneInput) aplicarMascara(telefoneInput, '(XX) XXXXX-XXXX');
});
</script>

    @include('partials.video-modal')

    <!-- Scripts do Modal -->
    <script>
        // Inicializa o modal quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica se já existe um modal aberto
            if (document.querySelector('.modal.show')) {
                return;
            }

            // Se o usuário já viu o vídeo, não mostra o modal
            if (localStorage.getItem('video_seen')) {
                return;
            }

            // Mostra o modal após 2 segundos
            setTimeout(function() {
                var modal = new bootstrap.Modal(document.getElementById('videoModal'));
                modal.show();
            }, 2000);
        });
    </script>

    <style>
        .video-container {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        video {
            border-radius: 4px;
            background: #000;
        }
    </style>

    <script>
        // Adicionando controles adicionais
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('videoPlayer');
            
            // Atualizando o tempo do vídeo
            video.addEventListener('timeupdate', function() {
                const progress = (video.currentTime / video.duration) * 100;
                document.getElementById('progress').style.width = progress + '%';
            });
        });
    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF
