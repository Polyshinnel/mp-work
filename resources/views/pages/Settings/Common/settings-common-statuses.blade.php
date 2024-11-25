@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end mb-3">
                <a href="/settings/common/site-status/add"><button type="button" class="btn btn-primary">Добавить статус</button></a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Список статусов</h3>

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
                            <th>ID статуса</th>
                            <th>Название</th>
                            <th>Цвет</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($siteStatusList)
                                @foreach($siteStatusList as $status)
                                    <tr>
                                        <td>{{$status['id']}}</td>
                                        <td>{{$status['site_status_id']}}</td>
                                        <td>{{$status['name']}}</td>
                                        <td><div class="color-block" style="width: 25px; height: 25px; background: {{$status['color']}}"></div></td>
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
