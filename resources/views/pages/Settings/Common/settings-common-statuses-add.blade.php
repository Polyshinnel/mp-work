@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Параметры статуса сайта</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/settings/common/site-status/add">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status_name">Название статуса</label>
                            <input type="text" class="form-control" id="status_name" name="status_name" placeholder="Резерв">
                        </div>
                        <div class="form-group">
                            <label for="status_id">Ид статуса на сайте(искать в коде симплы)</label>
                            <input type="text" class="form-control" id="status_id" name="status_id" placeholder="4">
                        </div>
                        <div class="form-group">
                            <label for="color">Цвет(hex)</label>
                            <input type="text" class="form-control" id="color" name="color" placeholder="#ff0000">
                        </div>
                        <div class="form-group">
                            <label for="watchable">Конечный</label>
                            <select class="form-control" name="watchable" id="watchable">
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
