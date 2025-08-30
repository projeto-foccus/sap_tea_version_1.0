{{-- Partial isolado do eixo Socioemocional --}}
<div class="socioemocional-bg" style="border-radius: 8px; padding: 18px; margin-bottom: 24px; box-shadow: 0 2px 8px #0001;">
  <div class="table-title" style="font-size:20px; color:#008060; text-align:center; margin-bottom:15px;">Eixo Socioemocional</div>

  {{-- REGISTROS JÁ CADASTRADOS - SOCIOEMOCIONAL --}}
  @if(isset($dadosMonitoramento['socioemocional']) && count($dadosMonitoramento['socioemocional']))
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
          @foreach($dadosMonitoramento['socioemocional'] as $cod => $registros)
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
      {{-- Aqui deve ser inserido o loop das atividades de socioemocional, igual ao bloco original --}}
      @php $idx = 0; @endphp
      @foreach($socioemocional_agrupado as $linha)
        @php
            $codigo = $linha->cod_ati_int_soc ?? $linha->cod_ati_int_socio;
            // Correção: Usar o valor normalizado para a contagem de linhas, em vez do total bruto.
            $key = 'soc_' . $codigo;
            $qtd = $norm_atividades[$key] ?? 0;
        @endphp
        @for($q=0; $q<$qtd; $q++)
          <tr data-eixo="socioemocional" data-idx="{{$idx}}" data-cod-atividade="{{ $linha->cod_ati_int_soc ?? $linha->cod_ati_int_socio }}">
            <td>
              {{ $linha->cod_ati_int_soc ?? $linha->cod_ati_int_socio }}-{{ $q + 1 }}
              <input type="hidden" name="socioemocional[{{$idx}}][cod_atividade]" value="{{ $linha->cod_ati_int_soc ?? $linha->cod_ati_int_socio }}">
              <input type="hidden" name="socioemocional[{{$idx}}][flag]" value="{{ $q + 1 }}">
            </td>
            <td>{{ $linha->desc_ati_int_soc ?? $linha->descricao ?? 'Descrição não disponível' }}</td>
            <td><input type="date" name="socioemocional[{{$idx}}][data_inicial]" class="form-control" value=""></td>
            <td class="text-center">
              <input type="checkbox" name="socioemocional[{{$idx}}][sim_inicial]" value="1" class="sim-checkbox" data-eixo="socioemocional" data-idx="{{$idx}}">
            </td>
            <td class="text-center">
              <input type="checkbox" name="socioemocional[{{$idx}}][nao_inicial]" value="1" class="nao-checkbox" data-eixo="socioemocional" data-idx="{{$idx}}">
            </td>
            <td><textarea name="socioemocional[{{$idx}}][observacoes]" class="form-control"></textarea></td>
            <td class="text-center">
              <button type="button" class="btn btn-success btn-salvar-linha" data-eixo="socioemocional" data-idx="{{$idx}}">Salvar atividade</button>
            </td>
          </tr>
          @php $idx++; @endphp
        @endfor
      @endforeach
    </tbody>
  </table>
</div>
