@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col">
            <h2>Bucket {{$bucketName}}</h2>
        </div>
        <div class="col">
            <div class="float-end">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Upload
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Data última modificação</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($bucketContent))
                    @foreach($bucketContent as $key => $object)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$object['Key']}}</td>
                            <td>{{$object['Owner']['DisplayName']}}</td>
                            <td>{{$object['LastModified']->format("d/m/Y H:i:s")}}</td>
                            <td>
                                <a class="mr-2" href="/bucket/{{$bucketName}}/objeto/{{$object['Key']}}/download" data-toggle="tooltip" data-placement="top" title="Download"><i class="bi bi-cloud-download"></i></a>
                                <a href="/bucket/{{$bucketName}}/objeto/{{$object['Key']}}/deletar" title="Remover"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fazer upload de arquivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form enctype="multipart/form-data" method="POST" action="/bucket/{{$bucketName}}/objeto/salvar">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Selecione o arquivo que deseja subir</label>
                            <input class="form-control" name="file" type="file" id="formFile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar Arquivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
