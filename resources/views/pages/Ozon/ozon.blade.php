@extends('layout.main-layout')
@section('username', $pageInfo['username'])
@section('page_title', $pageInfo['page_title'])
@section('block_title', $pageInfo['block_title'])

@section('content')
    @php($selectedFilters = $selectedFilters ?? [])
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
                    <div class="card-body card-body-table table-responsive p-0">
                        <div class="filter-block-unit">
                            <div class="filter-block-unit-block filter-block-unit-block_sm">
                                <div class="filter-block-unit__item">
                                    <div class="form-group">
                                        <label for="search-input">Поиск</label>
                                        <input type="text" class="form-control" id="search-input" name="search-input" placeholder="Поиск по таблице">
                                    </div>
                                </div>

                                <div class="filter-block-unit__item">
                                    <button type="button" class="btn btn-block btn-primary" id="update-site-data" data-toggle="modal" data-target="#modal-default">Обновить статусы</button>
                                </div>

                                <div class="filter-block-unit__item">
                                    <button type="button" class="btn btn-block btn-primary" id="update-ozon-orders" data-toggle="modal" data-target="#modal-default">Новые заказы</button>
                                </div>
                            </div>

                            <div class="filter-block-unit-block">
                                <div class="filter-block-unit__item">
                                    <div class="form-group">
                                        <label for="site_status">Статус на сайте</label>
                                        <select class="form-control" name="site_status" id="site_status">
                                            <option value="0" @selected(!isset($selectedFilters['site_status']))>Не выбрано</option>
                                            @if($filters['site_status'])
                                                @foreach($filters['site_status'] as $filter)
                                                    <option value="{{$filter['id']}}" @selected(($selectedFilters['site_status'] ?? null) == $filter['id'])>{{$filter['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-block-unit__item">
                                    <div class="form-group">
                                        <label for="warehouse_mark">Метка склада</label>
                                        <select class="form-control" name="warehouse_mark" id="warehouse_mark">
                                            <option value="0" @selected(!isset($selectedFilters['label']))>Не выбрано</option>
                                            @if($filters['site_label'])
                                                @foreach($filters['site_label'] as $filter)
                                                    <option value="{{$filter['id']}}" @selected(($selectedFilters['label'] ?? null) == $filter['id'])>{{$filter['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-block-unit__item">
                                    <div class="form-group">
                                        <label for="warehouse">Склад</label>
                                        <select class="form-control" name="warehouse" id="warehouse">
                                            <option value="0" @selected(!isset($selectedFilters['warehouse']))>Не выбрано</option>
                                            @if($filters['warehouse'])
                                                @foreach($filters['warehouse'] as $filter)
                                                    <option value="{{$filter['id']}}" @selected(($selectedFilters['warehouse'] ?? null) == $filter['id'])>{{$filter['name']}}</option>
                                                @endforeach
                                            @endif
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



                        </div>

                        @php
                            $firstOrder = $order_info->first();
                        @endphp


                        @if($firstOrder && $firstOrder['has_btn'])
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="download-labels-btn download-labels-btn-top">
                                            <button type="button" class="btn btn-block btn-primary" id="download-label1">Скачать наклейки</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="download-labels-btn download-labels-btn-top">
                                            <button type="button" class="btn btn-block btn-info" id="send-ks1">Отправить в кс</button>
                                        </div>
                                    </div>
                                </div>
                        @endif

                        <table id="order-table" class="table table-bordered table-striped" >
                            <thead>
                            <tr style="text-align: center" class="table-head">
                                <th><input type="checkbox" name="all" class="all-checkbox"></th>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Номер заказа</th>
                                <th>Номер заказа Озон</th>
                                <th>Статус на сайте</th>
                                <th>Метка склада</th>
                                <th>Склад Озон</th>
                                <th>Наклейка</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($order_info as $order)
                                        <tr style="text-align: center">
                                            <td><input type="checkbox" name="" id="" class="order-checkbox" data-item="{{$order['id']}}"></td>
                                            <td>{{$order['id']}}</td>
                                            <td>{{$order['date']['formatted_date']}}</td>
                                            <td><a target="_blank" href="{{$order['site_link']}}">{{$order['site_order']}}</a></td>
                                            <td><a target="_blank" href="{{$order['ozon_link']}}">{{$order['ozon_posting_id']}}</a></td>
                                            <td>
                                                <div style="border: 1px solid #ccc;border-radius: 20px; padding: 5px; background: {{$order['site_status_color']}}; text-align: center;">
                                                    {{$order['site_status_name']}}
                                                </div>
                                            </td>
                                            <td>
                                                <div style="border: 1px solid #ccc;border-radius: 20px; padding: 5px; background: {{$order['site_label_color']}}; text-align: center;">
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
                                            <td>
                                                @if($order['site_status_name'] != 'Резерв')
                                                    <a href="/site/updateOrder?orders[]={{$order['id']}}"><button type="button" class="btn btn-block btn-primary">В КС</button></a>
                                                @else
                                                    <button type="button" class="btn btn-block btn-primary disabled">В КС</button>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="/ozon-list/update-data/{{$order['id']}}"><button type="button" class="btn btn-block btn-primary"><i class="fas fa-sync"></i></button></a>
                                            </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" style="text-align: center">Нет данных для отображения</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($order_info->hasPages())
                            <div id="dataTables-server-pagination" class="d-none">
                                {{ $order_info->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif

                    </div>
                </div>

                @if($firstOrder && $firstOrder['has_btn'])
                        <div class="row">
                            <div class="col-md-6">
                                <div class="download-labels-btn download-labels-btn-top">
                                    <button type="button" class="btn btn-block btn-primary" id="download-label">Скачать наклейки</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="download-labels-btn download-labels-btn-top">
                                    <button type="button" class="btn btn-block btn-info" id="send-ks">Отправить в кс</button>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Идет обновление</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body__wrapper" style="display: flex; flex-direction: column; align-items: center;">
                        <img src="/assets/img/loading.gif" alt="" style="width: 130px;">
                        <p>Подождите, идет обновление, окно закроется автоматически</p>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
            pageLength: {{$order_info->perPage()}},
            lengthChange: false,
            paging: true,
            info: false,
            columnDefs: [ {
                targets: 0,
                orderable: false
            } ],
            oLanguage: {
                oPaginate: {
                    sFirst: "Первая", // This is the link to the first page
                    sPrevious: "Пред.", // This is the link to the previous page
                    sNext: "След.", // This is the link to the next page
                    sLast: "Последняя" // This is the link to the last page
                }
            }
        });

        const serverPaginationWrapper = $('#dataTables-server-pagination');

        const injectServerPagination = () => {
            if(!serverPaginationWrapper.length) {
                return;
            }

            const paginationHtml = serverPaginationWrapper.html();

            if(!paginationHtml || !paginationHtml.trim().length) {
                return;
            }

            const paginateContainer = $('#order-table_wrapper').find('.dataTables_paginate');
            if(!paginateContainer.length) {
                return;
            }
            paginateContainer.html(paginationHtml);
        };

        injectServerPagination();
        table.on('draw', injectServerPagination);

        $('#order-table_wrapper').on('click', '.pagination a', function (e) {
            const target = $(this).attr('href');
            if(!target || target === '#') {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            $('.all-checkbox').prop('checked', false);
            $('.order-checkbox').prop('checked', false);
            window.location.href = target;
        });

        $('#search-input').on('keyup', function () {
            table.search(this.value).draw();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#filter-data').click(function (){
            let siteStatus = $('#site_status').val()
            let siteLabel = $('#warehouse_mark').val()
            let warehouse = $('#warehouse').val()

            let filter = {};
            if(siteStatus != 0) {
                filter.status = siteStatus
            }
            if(siteLabel != 0) {
                filter.label = siteLabel
            }

            if(warehouse != 0) {
                filter.warehouse = warehouse
            }

            if(!jQuery.isEmptyObject(filter)){
                let path = window.location.pathname;
                let searchParam = new URLSearchParams(filter)
                searchParam = searchParam.toString()
                path = `${path}?${searchParam}`
                window.location.href = path
            }
        })

        $('#reset-data').click(function () {
            window.location.href = window.location.pathname
        })

        $('.all-checkbox').click(function () {
            if($(this).is(':checked'))
            {
                $('.order-checkbox').each(function () {
                    $(this).prop('checked', true)
                })
            } else {
                $('.order-checkbox').each(function () {
                    $(this).prop('checked', false)
                })
            }
        })

        function downloadLabel()
        {
            let idArr = []
            $('.order-checkbox:checked').each(function () {
                idArr.push($(this).attr('data-item'))
            })
            if(idArr.length > 0) {

                let path = '/ozon/getLabels?';
                for(let i = 0; i < idArr.length; i++)
                {
                    if(i == 0)
                    {
                        path += 'orders[]='+idArr[i]
                    }else {
                        path += '&orders[]='+idArr[i]
                    }
                }
                window.location.href = path
            } else {
                alert('Выберите хотя бы один чекбокс!')
            }
        }

        function updateOrders()
        {
            let idArr = []
            $('.order-checkbox:checked').each(function () {
                idArr.push($(this).attr('data-item'))
            })
            if(idArr.length > 0) {

                let path = '/site/updateOrder?';
                for(let i = 0; i < idArr.length; i++)
                {
                    if(i == 0)
                    {
                        path += 'orders[]='+idArr[i]
                    }else {
                        path += '&orders[]='+idArr[i]
                    }
                }
                window.location.href = path
            } else {
                alert('Выберите хотя бы один чекбокс!')
            }
        }

        $('#download-label, #download-label1').click(function () {
            downloadLabel()
        })

        $('#send-ks1, #send-ks').click(function () {
            updateOrders()
        })

        $('#update-ozon-orders').on('click', function () {
            $.ajax({
                url: '/ozon-list/synchronize',
                method: 'get',
                dataType: 'json',
                success: function(data){
                    window.location.reload()
                }
            });
        })

        $('#update-site-data').on('click', function () {
            $.ajax({
                url: '/ozon-list/update-data',
                method: 'get',
                dataType: 'json',
                success: function(data){
                    window.location.reload()
                }
            });
        })


        })
    </script>
@endsection
