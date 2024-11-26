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
                            <tr style="text-align: center">
                                <th></th>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Номер заказа</th>
                                <th>Номер заказа Озон</th>
                                <th>Статус на сайте</th>
                                <th>Метка склада</th>
                                <th>Склад Озон</th>
                                <th>Наклейка</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if($order_info)
                                    @foreach($order_info as $order)
                                        <tr style="text-align: center">
                                            <td><input type="checkbox" name="" id=""></td>
                                            <td>{{$order['id']}}</td>
                                            <td>{{$order['date']['formatted_date']}}</td>
                                            <td><a href="{{$order['site_link']}}">{{$order['site_order']}}</a></td>
                                            <td><a href="{{$order['ozon_link']}}">{{$order['ozon_posting_id']}}</a></td>
                                            <td>
                                                <div style="border-radius: 20px; padding: 5px; background: {{$order['site_status_color']}}; text-align: center;">
                                                    {{$order['site_status_name']}}
                                                </div>
                                            </td>
                                            <td>
                                                <div style="border-radius: 20px; padding: 5px; background: {{$order['site_label_color']}}; text-align: center;">
                                                    {{$order['site_label_name']}}
                                                </div>
                                            </td>
                                            <td>{{$order['warehouse_name']}}</td>
                                            <td>
                                                @if($order['has_btn'])
                                                    <button type="button" class="btn btn-block btn-primary">Скачать</button>
                                                @else
                                                    <button type="button" class="btn btn-block btn-primary disabled">Скачать</button>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
