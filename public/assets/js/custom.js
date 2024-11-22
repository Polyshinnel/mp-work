
$( document ).ready(function() {
    let location = window.location.pathname;
    console.log(location)
    $('.nav-sidebar .nav-item .nav-link').each(function () {
        $(this).removeClass('active')
        let sidebarLink = $(this).attr('href');
        if(sidebarLink !== '/') {
            if(location.includes(sidebarLink)) {
                $(this).addClass('active')
            }
        }
        if(sidebarLink === location) {
            $(this).addClass('active')
        }
    })

    let filterBtn = $('.filter-btn')
    let intervalBlock = $('.interval-block')

    filterBtn.click(function () {
        $(this).removeClass('btn-outline-primary')
        $(this).addClass('btn-primary')
        $('.filter-block-list').addClass('d-flex')
    })

    let locationQuery = window.location.search;
    locationQuery = locationQuery.substr(1);
    let locationQueryArr = locationQuery.split('&')
    if(locationQueryArr[0].length > 0) {
        let queryObj = {};
        for(let i = 0; i < locationQueryArr.length; i++) {
            let queryArr = locationQueryArr[i].split('=');
            let paramName = queryArr[0].trim()
            queryObj[paramName] = queryArr[1].trim()
        }

        if(queryObj.date_info) {
            filterBtn.removeClass('btn-outline-primary')
            filterBtn.addClass('btn-primary')
            $('.filter-block-list').addClass('d-flex')

            let buttons = $('.filter-block-list button')
            buttons.each(function () {
                $(this).removeClass('btn-primary')
                $(this).addClass('btn-outline-primary')
                if($(this).hasClass(queryObj.date_info)) {
                    $(this).removeClass('btn-outline-primary')
                    $(this).addClass('btn-primary')
                }
            })

            if(queryObj.date_info == 'interval') {
                intervalBlock.removeClass('d-none')
                if(queryObj.date_start) {
                    $('#date_start').val(queryObj.date_start)
                }

                if(queryObj.date_end) {
                    $('#date_end').val(queryObj.date_end)
                }
            }
        }
    }

    $('.month').click(function (){
        let link = location
        document.location.href = location + '?date_info=month'
    })

    $('.today').click(function (){
        let link = location
        document.location.href = location + '?date_info=today'
    })

    $('.yesterday').click(function (){
        let link = location
        document.location.href = location + '?date_info=yesterday'
    })

    $('.interval').click(function () {
        intervalBlock.removeClass('d-none')
        let buttons = $('.filter-block-list button')
        buttons.each(function () {
            $(this).removeClass('btn-primary')
            $(this).addClass('btn-outline-primary')
            if($(this).hasClass('interval')) {
                $(this).removeClass('btn-outline-primary')
                $(this).addClass('btn-primary')
            }
        })
    })

    $('.interval-accept').click(function () {
        let date_start = $('#date_start').val()
        let date_end = $('#date_end').val()
        let link = location+'?date_info=interval'
        if(date_start !== '') {
            link +='&date_start='+date_start
        }
        if(date_end !== '') {
            link +='&date_end='+date_end
        }
        document.location.href = link
    })
})



