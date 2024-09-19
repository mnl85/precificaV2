@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dados da Sessão</h1>
    <pre>{{ print_r(session()->all(), true) }}</pre>
</div>
@endsection


