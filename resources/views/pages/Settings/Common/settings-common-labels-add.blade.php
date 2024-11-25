@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Метка на сайте</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/settings/common/marks/add">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="label_name">Название метки</label>
                            <input type="text" class="form-control" id="label_name" name="label_name" placeholder="Склад готово">
                        </div>
                        <div class="form-group">
                            <label for="label_id">Ид метки(смотреть в коде симплы)</label>
                            <input type="text" class="form-control" id="label_id" name="label_id" placeholder="16">
                        </div>
                        <div class="form-group">
                            <label for="color">Цвет(hex)</label>
                            <input type="text" class="form-control" id="color" name="color" placeholder="#ff0000">
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
