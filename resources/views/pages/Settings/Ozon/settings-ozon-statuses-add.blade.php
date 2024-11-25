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
                <form method="POST" action="/settings/create">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="site-addr">Адрес сайта</label>
                        </div>
                        <div class="form-group">
                            <label for="payment_id">Платежная система</label>
                        </div>
                        <div class="form-group">
                            <label for="shop_id">Shop Id</label>
                            <input type="text" class="form-control" id="shop_id" name="shop_id" placeholder="Shop Id">
                        </div>
                        <div class="form-group">
                            <label for="api_key">Api Key</label>
                            <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Api Key">
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
