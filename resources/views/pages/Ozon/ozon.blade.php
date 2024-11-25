@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a href="/ozon-list" class="nav-link active" aria-controls="custom-tabs-four-profile" aria-selected="true">Ожидают сборки</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/awaiting-delivery" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">Ожидают отгрузки</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/delivery" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">Доставляются</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/arbitration" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">Спорные</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/delivered" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">Доставлены</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/canceled" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">Отменены</a>
                    </li>
                    <li class="nav-item">
                        <a href="/ozon-list/all" class="nav-link" aria-controls="custom-tabs-four-home" aria-selected="false">Все</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Сайт</th>
                                <th>Номер заказа</th>
                                <th>Номер заказа Озон</th>
                                <th>Статус на сайте</th>
                                <th>Метка склада</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td>1</td>
                                    <td>25.11.2024 21:27:19</td>
                                    <td>https://djecoshop.ru/</td>
                                    <td>34592</td>
                                    <td>0124861566-0018-4</td>
                                    <td>Принят</td>
                                    <td>Cклад - сборка</td>
                                    <td><button>Скачать наклейку</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
