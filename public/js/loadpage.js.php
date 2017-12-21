<script>
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    $(document).ready(function () {
        $('.loading_image').bind('ajaxStart', function () {
            $(this).show();
        }).bind('ajaxStop', function () {
            $(this).hide();
        });
        var hash = window.location.hash;
//        alert(hash);
        var hash = hash.substring(1);
        var urlarr = hash.split("_");
        var module = '';
        var controller = '';
        if (urlarr.length > 1) {
            //module = urlarr[0];
            controller = urlarr[1];
        } else {
            //module = urlarr[0];
            controller = urlarr[0];
        }

        if (controller == '')
        {
            //module = 'admin';
            controller = 'dashboard';
            //hash = module;
        }
        var requestUrl = "/admin/" + controller + "/index";
        window.location.hash = hash;

        $.ajax({
            type: '<?php echo ($_POST) ? 'POST' : 'GET'; ?>',
            url: requestUrl,
            data: '<?php echo ($_POST) ? http_build_query($_POST) : ''; ?>',
            async: true,
            success: function (result) {
                //alert(result);
                $("#loadpage").html(result);
                return false;
            }
        });
    });
</script>