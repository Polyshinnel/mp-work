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
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
