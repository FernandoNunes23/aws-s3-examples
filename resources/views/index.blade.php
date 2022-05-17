@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="float-end">
                <a type="button" href="/bucket/criar" class="btn btn-primary">Criar Bucket</a>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Região</th>
                    <th scope="col">Data de Criação</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($buckets as $key => $bucket)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$bucket['Name']}}</td>
                            <td>{{$bucket['Region']}}</td>
                            <td>{{$bucket['CreationDate']->format("d/m/Y H:i:s")}}</td>
                            <td>
                                <a class="mr-2" href="/bucket/{{$bucket['Name']}}" data-toggle="tooltip" data-placement="top" title="Ver"><i class="bi bi-eye"></i></a>
                                <a class="mr-2" href="/bucket/{{$bucket['Name']}}/editar" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="/bucket/{{$bucket['Name']}}/deletar" title="Remover"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
