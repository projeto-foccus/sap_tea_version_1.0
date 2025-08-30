@extends('index')

@section('styles')
@endsection

@section('content')
<div style="max-width:540px;margin:40px auto;background:#fff;border-radius:10px;box-shadow:0 2px 8px #0001;padding:30px 24px;font-family:Arial,sans-serif;">
    <h2 style="text-align:center;color:#1976d2;margin-bottom:22px;">@if(isset($titulo)){{ $titulo }}@else Material @endif</h2>
    @if(isset($erro) && $erro)
        <div style="color:#c00;text-align:center;font-weight:bold;margin-bottom:20px;">{{ $erro }}</div>
        <div style="text-align:center;"><a href="{{ route('index') }}" class="btn btn-danger">Voltar</a></div>
    @else
        <div style="font-size:16px;margin-bottom:16px;text-align:center;">
            Clique no botão abaixo para acessar o material no Google Drive.<br>
            <small style="color:#555;">(Na página do Drive, use o botão "Fazer download" para baixar todos os arquivos ou selecione arquivos individuais.)</small>
        </div>
        <div style="text-align:center;margin:22px 0;">
            <a href="{{ $link }}" target="_blank" class="btn btn-primary" style="padding:12px 32px;font-size:17px;">Acessar Material</a>
        </div>
    @endif
</div>
@endsection
