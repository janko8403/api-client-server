function showMenu() {
    $('.site-container').removeClass('menu-off').addClass('menu-on')
    $('.main-header').removeClass('full-width')
}

function hideMenu() {
    $('.site-container').removeClass('menu-on').addClass('menu-off')
    $('.main-header').addClass('full-width')
}

$('#sidebar-toggle').click(function (e) {
    e.preventDefault()
    $('.main-sidebar').toggleClass('toggled')
    if ($('.main-sidebar').hasClass('toggled')) {
        // hide
        hideMenu()
        localStorage.setItem('menu-hidden', '1')
    } else {
        // show
        showMenu()
        localStorage.setItem('menu-hidden', '0')
    }
})

$(document).ready(function () {
    // check is menu should be hidden
    const menuHidden = parseInt(localStorage.getItem('menu-hidden') || '0')
    if (menuHidden) {
        $('.main-sidebar').addClass('toggled')
        hideMenu()
    }

    var imgRotateDeg = 0
    $('.side a').click(function () {
        const lastClicked = $(this).attr('data-cookie')
        localStorage.setItem('lastClick', lastClicked)
    })
    if (localStorage.getItem('lastClick') != null && typeof localStorage.getItem('lastClick') != 'undefined') {
        const data_cookie = localStorage.getItem('lastClick')
        const element = $('[data-cookie="' + data_cookie + '"]')
        if (data_cookie.length > 2) {
            element.parent().parent().show()
        }
        element.parent().css('color', 'red')
    }

    $('.tree-toggle').click(function () {
        $(this).next().toggle('slow')
    })
    // $('.select2').select2({
    //     theme: "bootstrap"
    // });

    // @ts-ignore
    $('div[id^="myCarousel"]').carousel({
        pause: true,
        interval: false,
    })

    $('#content').on('click', '.carousel-inner img', function (e) {
        const src = $(this).attr('src')
        bootbox.alert('<img src=\'' + src + '\' class=\'img-responsive\' />')

        return false
    })
    $('#content').on('click', '.evaluation-rotate-left', function (e) {
        const src = $(this).parent().parent().find('img')
        console.log(src)
        imgRotateDeg -= 90
        src.css({
            '-ms-transform': 'rotate(' + imgRotateDeg + 'deg)',
            '-webkit-transform': 'rotate(' + imgRotateDeg + 'deg)',
            'transform': 'rotate(' + imgRotateDeg + 'deg)',
        })

        return false
    })
    $('#content').on('click', '.evaluation-rotate-right', function (e) {
        const src = $(this).parent().parent().find('img')
        console.log(src)
        imgRotateDeg += 90
        src.css({
            '-ms-transform': 'rotate(' + imgRotateDeg + 'deg)',
            '-webkit-transform': 'rotate(' + imgRotateDeg + 'deg)',
            'transform': 'rotate(' + imgRotateDeg + 'deg)',
        })
        return false
    })
    $('.evaluation-download').click(function (e) {
        e.stopPropagation()
        // e.preventDefault();
    })
    $('#content').on('click', '.history-back', function (e) {
        e.preventDefault()
        console.log('click')
        window.history.back()
        // window.location.reload();
    })
})

if ($('#back-to-top').length) {
    const scrollTrigger = 100, // px
        backToTop = function () {
            const scrollTop = $('.site-container').scrollTop()
            if (scrollTop > scrollTrigger) {
                $('#back-to-top').addClass('show')
            } else {
                $('#back-to-top').removeClass('show')
            }
        }
    backToTop()
    $('.site-container').on('scroll', function () {
        backToTop()
    })
    $('#back-to-top').on('click', function (e) {
        e.preventDefault()
        $('.site-container').animate({
            scrollTop: 0,
        }, 700)
    })
}

$(document).ajaxStart(function () {
    // @ts-ignore
    if (!window.disableSpinner) {
        $('#bodyLoadingOverlay').show()
    }
})
$(document).ajaxStop(function () {
    // @ts-ignore
    if (!window.disableSpinner) {
        $('#bodyLoadingOverlay').hide()
    }
})
$(document).ajaxError(function (event, xhr, settings) {
    if (xhr.status == 403) {
        let url = '/login'
        const path = window.location.pathname

        if (path) {
            url += '?return=' + path
        }

        window.location.href = url
    }
})