$(document).ready(function () {

    var id_usuario = $('#id_usuario').val();
    var id_curso = $('#id_curso').val();
    var url_master = $('#url_master').val();

    $( ".icon_menu" ).hover(function() {
        $('#txt_menu').toggleClass('ocultar');
    });

    // BTN MENSAJES
    $('.bnt_sms').on('click', function () {
        var valor = $(this).attr('value');
        var metodo = "insert_check_sms";
        var parametros = {
            id_usuario:id_usuario,  
            recibe_sms:valor 
        };
        var send_post = post_ajax(metodo, parametros);
    });



    // BTN REGRESAR
    $('.regresa_dash').on('click', function () {
        var id_recurso = $(this).attr('back');
        $('.recursos').addClass('ocultar');
        $('.capitulos').addClass('ocultar');
        $('#dash_capitulos').removeClass('ocultar');
    });


        // Abrir Capitulo
    $('.open_contenido').on('click', function () {
        var id_recurso = $(this).attr('recurso');
        $('.capitulos').addClass('ocultar');
        $('.recursos').addClass('ocultar');
        $('#dash_capitulos').addClass('ocultar');
        $('#'+id_recurso).removeClass('ocultar');

    });

    // Abrit Videos
    $('.open_video').on('click', function () {
        var id_recurso = $(this).attr('recurso');
        var enlace = $(this).attr('enlace');
        var intro = $(this).attr('intro');
        
        $('#dash_capitulos').addClass('ocultar');
        $('.capitulos').addClass('ocultar');
        $('.recursos').addClass('ocultar');
        $('.videos_rec').empty();
        $('#recurso_'+id_recurso).empty();
        $('#recurso_'+id_recurso).append('<iframe src="'+enlace+'?autoplay=1&enablejsapi=1" class="iframe_res" frameborder="0"></iframe><br><p>'+intro+'</p>');
        var check = check_ancla(id_recurso);



    });

    // SECUENCIA
    $('.btn_control').on('click', function () {
        var secuencia = $(this).attr('secuencia');
        var valor = $(this).attr('value');
        var x = eval(secuencia+(valor));
        $('.recursos').addClass('ocultar');
        $('.capitulos').addClass('ocultar');
        $('.videos_rec').empty();
        
        var id_recurso = $('.'+x).attr('id');
        var enlace = $('.'+id_recurso).attr('enlace');
        var intro = $('.'+id_recurso).attr('intro');

        $('#recurso_'+id_recurso).empty();
        $('#recurso_'+id_recurso).append('<iframe src="'+enlace+'?autoplay=1&enablejsapi=1" class="iframe_res" frameborder="0"></iframe><br><p>'+intro+'</p>');

        $('.'+x).removeClass('ocultar');
        var check = check_ancla(id_recurso);
    });


    // SECUENCIA CAPITULOS
    $('.btn_sec_cap').on('click', function () {
        var secuencia = $(this).attr('secuencia');
        var valor = $(this).attr('value');
        var x = eval(secuencia+(valor));
        $('.recursos').addClass('ocultar');
        $('.capitulos').addClass('ocultar');
        $('.videos_rec').empty();

        var id_recurso = $('.cap_'+x).attr('id');
        $('.cap_'+x).removeClass('ocultar');
    });


    var check_ancla = function(id_recurso) {
        $("#enlace_menu_"+id_recurso+" span").removeClass("ocultar");
        $('#ancla_'+id_recurso).attr('checked','true');
        
        var id_contenido = $('#ancla_'+id_recurso).attr('id_recurso');
        var id_modulo = $('#ancla_'+id_recurso).attr('id_modulo');
        var id_seccion = $('#ancla_'+id_recurso).attr('id_seccion');
        var metodo = "marcar_ancla";
        var parametros = {
            id_usuario:id_usuario, 
            id_curso:id_curso, 
            id_seccion:id_seccion, 
            id_modulo:id_modulo, 
            id_contenido:id_contenido 
        };
        var send_post = post_ajax(metodo, parametros);
        $('#'+id_recurso).removeClass('ocultar');
        return 0;
    };

    var post_ajax = function(metodo, parametros) {
        var url_post = url_master+"/interfaz_angeles/php/ajax_rta.php";
         var arrayData = {
            metodo:metodo, 
            parametros:parametros 
        };
        $.ajax({
            url: url_post,
            type: "POST",
            data: arrayData,
            success: function(rta){
                console.log(rta);
            }
        });
        return 0;
    };


});