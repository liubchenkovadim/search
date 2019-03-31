$(document).ready(function () {
    var url =document.location.href;
    var path = findGetParameter('path');
    var product_id = findGetParameter('product_id');
    $.ajax({
        type: 'post',
        url: $('base').attr('href') + 'index.php?route=extension/module/pinta_schema_org/schema',
        data: {'url': url,'path':path,'product_id':product_id},
        response: 'json',
        success: function (data) {
            $('footer').after(data);
           console.log(data);

        }
    });


    function findGetParameter(parameterName) {
        var result = null,
            tmp = [];
        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
                tmp = item.split("=");
                if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }

});