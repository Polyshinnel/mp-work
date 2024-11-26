@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Данные сайта</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/settings/common/sites/add">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="site_name">Адрес сайта</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="https://paolareinas.ru/">
                        </div>
                        <div class="form-group">
                            <label for="site_prefix">Перфикс</label>
                            <input type="text" class="form-control" id="site_prefix" name="site_prefix" placeholder="pr">
                        </div>
                        <div class="form-group">
                            <label for="site_db">Имя бд</label>
                            <input type="text" class="form-control" id="site_db" name="site_db" placeholder="paola">
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
