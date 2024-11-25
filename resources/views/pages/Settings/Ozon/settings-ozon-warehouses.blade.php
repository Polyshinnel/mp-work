@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end mb-3">
                <a href="/settings/ozon-settings/warehouses/add"><button type="button" class="btn btn-primary">Добавить склад</button></a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Список складов OZON</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Поиск">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID склада</th>
                            <th>Название</th>
                            <th>Тип</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($warehouseList)
                                @foreach($warehouseList as $warehouse)
                                    <tr>
                                        <td>{{$warehouse['id']}}</td>
                                        <td>{{$warehouse['warehouse_id']}}</td>
                                        <td>{{$warehouse['name']}}</td>
                                        <td>{{$warehouse['type']}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection
