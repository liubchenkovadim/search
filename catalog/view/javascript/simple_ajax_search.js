$(document).ready(function () {
    $('#simple_ajax_search').bind('keyup', function () {
        var search = $('#simple_ajax_search').val();
        console.log(search);
        $.ajax({
            type: 'post',
            url: $('base').attr('href') + 'index.php?route=extension/module/simple/searchs',
            data: {'search': search},
            response: 'json',
            success: function (data) {
                $(".search_result").html(data).fadeIn(); //Выводим полученые данные в списке
            }
        });
        $(".search_result").hover(function () {
            $(".who").blur(); //Убираем фокус с input
        });

//При выборе результата поиска, прячем список и заносим выбранный результат в input
        $(".search_result").on("click", "li", function () {
            s_user = $(this).text();
            //$(".who").val(s_user).attr('disabled', 'disabled'); //деактивируем input, если нужно
            $(".search_result").fadeOut();
        })

    })

});



