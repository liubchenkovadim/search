$(document).ready(function () {
    if($("#pinta_schema_org_select_organization").prop("checked")){
        $('.organization').removeClass('hidden_tr');
        $('.organization-url').each(function (){
            $(this).removeClass('hidden_tr');
        });
        $('.organization-telefon').each(function (){
            $(this).removeClass('hidden_tr');
        });

    } else {
        $('.organization').addClass('hidden_tr');
        $('.organization-url').each(function (){
            $(this).addClass('hidden_tr');
        });
        $('.organization-telefon').each(function (index,value){
            $(this).addClass('hidden_tr');
        });
    }
    $("#pinta_schema_org_select_organization").change(function () {
        if (this.checked) {
            $('.organization').removeClass('hidden_tr');
            $('.organization-url').each(function (){
                $(this).removeClass('hidden_tr');
            });
            $('.organization-telefon').each(function (){
                $(this).removeClass('hidden_tr');
            });

        } else {
            $('.organization').addClass('hidden_tr');
            $('.organization-url').each(function (){
                $(this).addClass('hidden_tr');
            });
            $('.organization-telefon').each(function (index,value){
                $(this).addClass('hidden_tr');
            });

        }
    });

    $('a.add_telefon').on('click',function (event) {
        event.preventDefault();
        var index = $('.telefon').length;
        $('.organization-telefon:last').after( '<tr class="organization-telefon"><td><input type="text" class="form-control" " name="pinta_schema_org_select_organization_telefon['+index+']" ></td></tr>');
    });

    $('a.add_url').on('click',function (event) {
        event.preventDefault();
        var index = $('.url').length;
        console.log(index);
        $('.organization-url:last').after( '<tr class="organization-url"><td><input type="text" class="form-control" " name="pinta_schema_org_select_organization_url['+index+']" ></td></tr>');
    });

    $("a.delete_url").on('click',function (event) {
        event.preventDefault();
        var clicked = $(this);
        var a =clicked.closest('.organization-url').find('.form-control.url').attr('name');
        if(a != 'pinta_schema_org_select_organization_url[0]'){
        clicked.parents('tr.organization-url').remove();
        } else {
            clicked.after('<p style="color:red;" >this field cannot be removed</p> ')
        }
    });
    $("a.delete_telefon").on('click',function (event) {
        event.preventDefault();
        var clicked = $(this);
        var b =clicked.closest('.organization-telefon').find('.form-control.telefon').attr('name');
        if(b != 'pinta_schema_org_select_organization_telefon[0]'){
            clicked.parents('tr.organization-telefon').remove();
        } else {
            clicked.after('<p style="color:red;" >The last field cannot be deleted!</p> ')
        }
    });

    if($("#pinta_schema_org_select_place").prop("checked")){
        $('.adress').removeClass('hidden_tr');
    } else {
        $('.adress').addClass('hidden_tr');

    };

    $("#pinta_schema_org_select_place").change(function () {
        if (this.checked) {

            $('.adress').each(function (){
                $(this).removeClass('hidden_tr');
            });

        } else {

            $('.adress').each(function (){
                $(this).addClass('hidden_tr');
            });


        }
    });
    if($("#pinta_schema_org_select_times").prop("checked")){
        $('.time-tr').removeClass('hidden_tr');
    } else {
        $('.time-tr').addClass('hidden_tr');

    };

    $("#pinta_schema_org_select_times").change(function () {
        if (this.checked) {

            $('.time-tr').each(function (){
                $(this).removeClass('hidden_tr');
            });

        } else {

            $('.time-tr').each(function (){
                $(this).addClass('hidden_tr');
            });


        }
    });

    $('a.add_time').on('click',function (event) {
        event.preventDefault();
        var index = $('.time-tr').length;

        $('tr.time-tr:last').after( '<tr class="time-tr"><td></td><td><input type="text" class="form-control" name="pinta_schema_org_select_time_full['+index+'][day]" placeholder="{{ placeholder_day }}"></td><td></td><td><input type="text" class="form-control" name="pinta_schema_org_select_time_full['+index+'][time]" placeholder="{{ placeholder_day }}"></td></tr>');
    });

    $("a.delete_time").on('click',function (event) {
        event.preventDefault();
        var clicked = $(this);
              var c =clicked.closest('.time-tr').find('.form-control.day').attr('name');

        if(c != 'pinta_schema_org_select_time_full[0][day]'){
            clicked.parents('tr.time-tr').remove();
        } else {
            var tr = clicked.closest('.table.border_none.schema_org').find('.time-tr');
            var er = clicked.closest('.table.border_none.schema_org').find('.erorr').length;
            if (er == 0) {
                tr.before('<tr class="erorr"><td colspan="6"><p style="color:red;" >The last field cannot be deleted!</p> </td> </tr>')
            }
        }

    });
});