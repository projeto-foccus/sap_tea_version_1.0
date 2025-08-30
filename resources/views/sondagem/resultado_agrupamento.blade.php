@extends('layouts.app')
 
@section('content')
<div class="container">
    <h2>Resultados Agrupados por Proposta</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>fk_id_pro_com_lin</th>
                <th>Total de Marcações</th>
            </tr>
        </thead>
        <tbody>
            @php $soma = 0; @endphp
            @foreach ($agrupamento as $item)
                <tr>
                    <td>{{ $item->fk_id_pro_com_lin }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
                @php $soma += $item->total; @endphp
            @endforeach
            <tr style="font-weight: bold; background: #f0f0f0;">
                <td>Total Geral (iguais a 1)</td>
                <td>{{ $soma }}</td>
            </tr>
            <tr style="font-weight: bold; background: #e0e0e0;">
                <td>Total de respostas iguais a 0</td>
                <td>{{ $total_zeros }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('index') }}" class="btn btn-primary">Voltar</a>

    <div class="mt-4">
        <h5>Campos com resposta igual a 0:</h5>
        @if (count($lista_zeros) > 0)
            <ul>
                @foreach ($lista_zeros as $zero)
                    <li>{{ $zero }}</li>
                @endforeach
            </ul>
        @else
            <p>Nenhum campo marcado como 0.</p>
        @endif
    </div>
</div>
@endsection
