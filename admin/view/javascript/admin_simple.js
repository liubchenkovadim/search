$(document).ready(function () {
        if($("#desing_title_cropping").prop("checked")){
            $('#td_cropping').removeClass('hidden_ul');

        }
    $("#desing_title_cropping").change(function () {
        if (this.checked) {
                $('#td_cropping').removeClass('hidden_ul');
            } else {
                    $('#td_cropping').addClass('hidden_ul');

                }
    });

        if($("#appearance_background_hover_on").prop("checked")){
        $('#collor_on').removeClass('hidden_ul');
        }
    $("#appearance_background_hover_on").change(function () {
        if (this.checked) {
            $('#collor_on').removeClass('hidden_ul');
        } else {
            $('#collor_on').addClass('hidden_ul');

        }
    });
    if($("#appearance_background_hover_gradient_on").prop("checked")){
        $('#gradiet_on').removeClass('hidden_ul');
    }
    $("#appearance_background_hover_gradient_on").change(function () {
        if (this.checked) {
            $('#gradiet_on').removeClass('hidden_ul');
        } else {
            $('#gradiet_on').addClass('hidden_ul');

        }
    });
});