@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end mb-3">
                <a href="/settings/ozon-settings/statuses/add"><button type="button" class="btn btn-primary">Добавить статус</button></a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Список статусов OZON</h3>

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
                            <th>Ozon Статус</th>
                            <th>Название</th>
                            <th>Цвет</th>
                            <th>Проверять метки</th>
                            <th>Обновлять статус</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($statusList)
                                @foreach($statusList as $status)
                                    <tr>
                                        <td>{{$status['id']}}</td>
                                        <td>{{$status['ozon_status_name']}}</td>
                                        <td>{{$status['name']}}</td>
                                        <td><div class="color-block" style="width: 25px; height: 25px; background: {{$status['color']}}"></div></td>
                                        <td>
                                            @if($status['watch_label'])
                                                Да
                                            @else
                                                Нет
                                            @endif
                                        </td>
                                        <td>
                                            @if($status['watch_ozon_status'])
                                                Да
                                            @else
                                                Нет
                                            @endif
                                        </td>
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
