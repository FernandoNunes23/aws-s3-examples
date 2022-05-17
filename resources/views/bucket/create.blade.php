@extends('layout.app')
@section('content')
    <form method="POST" action="/bucket/salvar">
        @csrf
        <div class="form-group">
            <label >Nome</label>
            <input type="text" class="form-control" name="bucketName" placeholder="Bucket Name">
            <small class="form-text text-muted">O bucket name deve ser único.</small>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Selecione a região</label>
            <select class="form-control" name="region" id="exampleFormControlSelect1">
                <option value="none">Indiferente</option>
                <option value="us-east-2">US East (Ohio)</option>
                <option value="us-east-1">US East (N. Virginia)</option>
                <option value="us-west-1">US West (N. California)</option>
                <option value="us-west-2">US West (Oregon)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-5">Criar</button>
    </form>
@endsection
