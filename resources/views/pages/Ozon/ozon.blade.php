@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    @foreach($tabList as $tab)
                        @if($tab['active'])
                            <li class="nav-item">
                                <a href="{{$tab['url']}}" class="nav-link active" aria-controls="custom-tabs-four-profile" aria-selected="true">{{$tab['name']}}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{$tab['url']}}" class="nav-link" aria-controls="custom-tabs-four-profile" aria-selected="false">{{$tab['name']}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="card-body table-responsive p-0">
                        <div class="filter-block-unit">
                            <div class="filter-block-unit__item">
                                <div class="form-group">
                                    <label for="search-input">Поиск</label>
                                    <input type="text" class="form-control" id="search-input" name="search-input" placeholder="Поиск по таблице">
                                </div>
                            </div>

                            <div class="filter-block-unit__item">
                                <div class="form-group">
                                    <label for="site_status">Статус на сайте</label>
                                    <select class="form-control" name="site_status" id="site_status">
                                        <option value="0">Не выбрано</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter-block-unit__item">
                                <div class="form-group">
                                    <label for="warehouse_mark">Метка склада</label>
                                    <select class="form-control" name="warehouse_mark" id="warehouse_mark">
                                        <option value="0">Не выбрано</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter-block-unit__item">
                                <div class="form-group">
                                    <label for="warehouse">Склад</label>
                                    <select class="form-control" name="warehouse" id="warehouse">
                                        <option value="0">Не выбрано</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter-block-unit__item">
                                <button type="button" class="btn btn-block btn-primary" id="filter-data">Фильтровать</button>
                            </div>

                            <div class="filter-block-unit__item">
                                <button type="button" class="btn btn-block btn-default" id="reset-data">Сбросить</button>
                            </div>
                        </div>
                        <table id="order-table" class="table table-bordered table-striped">
                            <thead>
                            <tr style="text-align: center">
                                <th><input type="checkbox" name="all" id="all-checkbox"></th>
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
                                            <td><input type="checkbox" name="" id="" class="order-checkbox" data-item="{{$order['id']}}"></td>
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
                                                    <a href="/ozon/getLabels?orders[]={{$order['id']}}"><button type="button" class="btn btn-block btn-primary">Скачать</button></a>
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

    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/jszip/jszip.min.js"></script>
    <script src="/assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script>
        let table = $('#order-table').DataTable({
            order: [[0, 'desc']],
            pageLength: 20,
            lengthChange: false,
            oLanguage: {
                oPaginate: {
                    sFirst: "Первая", // This is the link to the first page
                    sPrevious: "Пред.", // This is the link to the previous page
                    sNext: "След.", // This is the link to the next page
                    sLast: "Последняя" // This is the link to the last page
                }
            }
        });

        let selector = $('#order-table_wrapper').find('.row .col-sm-12').first()
        $('#search-input').on('keyup', function () {
            table.search(this.value).draw();
        });
    </script>
@endsection
