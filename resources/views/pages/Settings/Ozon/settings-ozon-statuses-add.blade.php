@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Настройки статусов озона</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/settings/ozon-settings/statuses/add">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status_name">Имя статуса</label>
                            <input type="text" class="form-control" id="status_name" name="status_name" placeholder="Ожидают сборки">
                        </div>
                        <div class="form-group">
                            <label for="ozon_status_name">Имя статуса на Озон(Api)</label>
                            <input type="text" class="form-control" id="ozon_status_name" name="ozon_status_name" placeholder="awaiting_packaging">
                        </div>
                        <div class="form-group">
                            <label for="status_color">Цвет</label>
                            <input type="text" class="form-control" id="status_color" name="status_color" placeholder="#ff0000">
                        </div>
                        <div class="form-group">
                            <label for="watch_label">Запрашивать обновления метки</label>
                            <select class="form-control" name="watch_label" id="watch_label">
                                <option value="0">Нет</option>
                                <option value="1">Да</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="watch_ozon_status">Запрашивать обновления статусов</label>
                            <select class="form-control" name="watch_ozon_status" id="watch_ozon_status">
                                <option value="0">Нет</option>
                                <option value="1">Да</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
@endsection
