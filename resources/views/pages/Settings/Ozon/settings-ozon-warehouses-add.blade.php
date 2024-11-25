@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Настройки платежной системы</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/settings/ozon-settings/warehouses/add">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="warehouse_name">Название склада</label>
                            <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" placeholder="Ярославская">
                        </div>
                        <div class="form-group">
                            <label for="warehouse_id">Id склада(брать из Апи)</label>
                            <input type="text" class="form-control" id="warehouse_id" name="warehouse_id" placeholder="19294037531000">
                        </div>
                        <div class="form-group">
                            <label for="type">Тип склада</label>
                            <input type="text" class="form-control" id="type" name="type" placeholder="Экспресс">
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
