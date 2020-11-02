@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <sale :envio ="{{ $id }}"></sale>
            </div>
        </div>
    </div>
@endsection
@section('breadcrumb')
    Ventas
@endsection
