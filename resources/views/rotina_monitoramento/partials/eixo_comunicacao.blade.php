{{-- Partial isolado do eixo Comunicação/Linguagem --}}
<div class="comunicacao-bg" style="border-radius: 8px; padding: 18px; margin-bottom: 24px; box-shadow: 0 2px 8px #0001;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:15px;"
    ><div class="table-title" style="font-size:20px; color:#b28600; text-align:center;">Eixo Comunicação/Linguagem</div>
    @php
        // Determina o ID do estudante de forma segura
        $alunoId = null;
        if (is_array($alunoDetalhado) && isset($alunoDetalhado[0]) && isset($alunoDetalhado[0]->alu_id)) {
            $alunoId = $alunoDetalhado[0]->alu_id;
        } elseif (is_object($alunoDetalhado) && isset($alunoDetalhado->alu_id)) {
            $alunoId = $alunoDetalhado->alu_id;
        }
    @endphp
    <a href="{{ route('grafico.comunicacao', ['alunoId' => $alunoId]) }}" class="btn btn-primary d-none" style="background-color: #b28600; border-color: #b28600;"><i class="fas fa-chart-bar"></i> Ver Gráfico</a>
  </div>

  {{-- REGISTROS JÁ CADASTRADOS --}}
  @if(isset($dadosMonitoramento['comunicacao']) && count($dadosMonitoramento['comunicacao']))
    <div style="margin-bottom:12px;">
      <strong>Registros já cadastrados:</strong>
      <table class="result-table" style="margin-bottom:8px;">
        <thead>
          <tr style="background:#ffd966;">
            <th>Código</th>
            <th>Data</th>
            <th>Realizado?</th>
            <th>Observações</th>
            <th>Registro Timestamp</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dadosMonitoramento['comunicacao'] as $cod => $registros)
            @foreach($registros as $registro)
              <tr>
                <td>{{ $cod }}</td>
                <td>{{ $registro['data_aplicacao'] }}</td>
                <td><input type="checkbox" disabled @if($registro['realizado']) checked @endif></td>
                <td>{{ $registro['observacoes'] }}</td>
                <td>{{ $registro['registro_timestamp'] }}</td>
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

    <table class="result-table" style="background: #fff;">
    <thead>
      <tr style="background: #ffe066;">
        <th style="width: 8%;" rowspan="2">Atividade</th>
        <th style="width: 28%;" rowspan="2">Descrição</th>
        <th style="width: 12%;" rowspan="2">Data de aplicação</th>
        <th colspan="2" style="text-align:center;">Realizou a atividade com apoio?</th>
        <th style="width: 20%;" rowspan="2">Observações</th>
        <th style="width: 12%;" rowspan="2">Atividades:</th>
      </tr>
      <tr style="background: #ffe066;">
        <th style="width: 5%;">Sim</th>
        <th style="width: 5%;">Não</th>
      </tr>
    </thead>
    <tbody>
      @php $idx = 0; @endphp
      @foreach($comunicacao_linguagem_agrupado as $linha)
        @php
            // Normaliza código para comparação
            $codigo = strtoupper(trim($linha->cod_ati_com_lin));

            // Correção: Usar o valor normalizado para a contagem de linhas, em vez do total bruto.
            $key = 'com_' . $codigo;
            $qtd = $norm_atividades[$key] ?? 0;

            $contadoresCom[$codigo] = ($contadoresCom[$codigo] ?? 0) + 1;

            // Lógica para não exibir EIS01
            if ($codigo === 'EIS01') continue;
            // Verifica se já preencheu hoje
            $jaPreenchidoHoje = false;
            if (!empty($codigosPreenchidosCom[$codigo])) {
                foreach($codigosPreenchidosCom[$codigo] as $data) {
                    if ($data && substr($data,0,10) == date('Y-m-d')) {
                        $jaPreenchidoHoje = true;
                        break;
                    }
                }
            }
            if($jaPreenchidoHoje) continue;
        @endphp
        @for($q=0; $q<$qtd; $q++)
          <tr data-eixo="comunicacao" data-idx="{{$idx}}" data-cod-atividade="{{ $linha->cod_ati_com_lin }}">
            <td>
              {{ $linha->cod_ati_com_lin }}-{{ $q + 1 }}
              <input type="hidden" name="comunicacao[{{$idx}}][cod_atividade]" value="{{ $linha->cod_ati_com_lin }}">
              <input type="hidden" name="comunicacao[{{$idx}}][flag]" value="{{ $q + 1 }}">
            </td>
            <td>{{ $linha->desc_ati_com_lin }}</td>
            <td><input type="date" name="comunicacao[{{$idx}}][data_inicial]" class="form-control" value=""></td>
            <td class="text-center">
              <input type="checkbox" name="comunicacao[{{$idx}}][sim_inicial]" value="1" class="sim-checkbox" data-eixo="comunicacao" data-idx="{{$idx}}">
            </td>
            <td class="text-center">
              <input type="checkbox" name="comunicacao[{{$idx}}][nao_inicial]" value="1" class="nao-checkbox" data-eixo="comunicacao" data-idx="{{$idx}}">
            </td>
            <td><textarea name="comunicacao[{{$idx}}][observacoes]" class="form-control"></textarea></td>
            <td class="text-center">
              <button type="button" class="btn btn-success btn-salvar-linha" data-eixo="comunicacao" data-idx="{{$idx}}">Salvar atividade</button>
            </td>
          </tr>
          @php $idx++; @endphp
        @endfor
      @endforeach
    </tbody>
  </table>
</div>
