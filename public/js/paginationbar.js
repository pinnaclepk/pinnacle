// JavaScript Document
$(document).ready(function () {

    var total_rec = $('#tot_record').val();
    var rec_id = $('#id').val();
    var is_first = true;
    var is_last = false;
    var first = 0;
    var last = total_rec - 1 ;
    var prev = 0;
    var next = 1;
    var str = '';
    var val="logdata"
    var path = $(".url").text();
    if(total_rec == '1')
    {
        $('.popup_paging').hide();

        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home_disabled.gif");
        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left_disabled.gif");
        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right_disabled.gif");
        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end_disabled.gif");
        $('#next').click(function() {
            return false;
        });
        $('#last').click(function() {
            return false;
        });
        $('#first').click(function() {
            return false;
        });
        $('#prev').click(function() {
            return false;
        });

    }
    else
    {
        $('#first').click(function() {
            str = first;
            is_first = true;
            is_last = false;

            $.ajax({
                url:path,
                async: false,
                type : 'POST',
                data: {
                    record_no : str,
                    id : rec_id,
                    flag : val
                } ,
                success: function(data){
                    $('#mydiv').html(data);
                    if(!is_first)
                    {
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left.gif");

                    }
                    else
                    {
                        $("#first").attr("class","log_pagination first disabled");
                        $("#prev").attr("class","log_pagination previous disabled");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home_disabled.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left_disabled.gif");
                        $('#first').click(function() {
                            return false;
                        });
                        $('#prev').click(function() {
                            return false;
                        });

                    }

                    if(is_last)
                    {
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right_disabled.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end_disabled.gif");
                        $('#next').click(function() {
                            return false;
                        });
                        $('#last').click(function() {
                            return false;
                        });
                    }
                    else
                    {
                        $("#next").attr("class","log_pagination next");
                        $("#last").attr("class","log_pagination last");
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end.gif");
                    }
                }

            });
        });
        $("#prev").click(function() {

            str = prev;

            var rec = total_rec-1;
            if(prev > 0 )
            {

                next = prev + 1;
                prev--;
                is_last = false;

            }
            else
            {
                next = prev + 1;
                is_first = true;
                is_last = false;
            }
            $.ajax({

                url:path,
                async: false,
                type : 'POST',
                data: {
                    record_no : str,
                    id : rec_id,
                    flag : val
                } ,
                success: function(data){
                    $('#mydiv').html(data);
                    if(!is_first)
                    {
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left.gif");

                    }
                    else
                    {
                        $("#first").attr("class","log_pagination first disabled");
                        $("#prev").attr("class","log_pagination previous disabled");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home_disabled.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left_disabled.gif");
                        $('#first').click(function() {
                            return false;
                        });
                        $('#prev').click(function() {
                            return false;
                        });
                    }

                    if(is_last)
                    {
                        $("#next").attr("class","log_pagination next disabled");
                        $("#last").attr("class","log_pagination last disabled");
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right_disabled.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end_disabled.gif");
                        $('#next').click(function() {
                            return false;
                        });
                        $('#last').click(function() {
                            return false;
                        });
                    }
                    else
                    {
                        $("#next").attr("class","log_pagination next");
                        $("#last").attr("class","log_pagination last");
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end.gif");
                    }
                }
            });
        });
        $("#next").click(function() {
            str = next;

            var rec = total_rec-1;
            if(next < rec )
            {
                prev = next - 1;
                next++;
                is_first = false;

            }
            else
            {
                prev = next - 1;
                is_first = false;
                is_last = true;
            }

            $.ajax({

                url:path,
                async: false,
                type : 'POST',
                data: {
                    record_no : str,
                    id : rec_id,
                    flag : val
                } ,
                success: function(result){
                    $('#mydiv').html(result);
                    if(!is_first)
                    {
                        $("#first").attr("class","log_pagination first");
                        $("#prev").attr("class","log_pagination previous");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left.gif");

                    }
                    else
                    {
                        $("#first").attr("class","log_pagination first disabled");
                        $("#prev").attr("class","log_pagination previous disabled");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home_disabled.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left_disabled.gif");
                        $('#first').click(function() {
                            return false;
                        });
                        $('#prev').click(function() {
                            return false;
                        });
                    }

                    if(is_last)
                    {
                        $("#next").attr("class","log_pagination next disabled");
                        $("#last").attr("class","log_pagination last disabled");
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right_disabled.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end_disabled.gif");
                        $('#next').click(function() {
                            return false;
                        });
                        $('#last').click(function() {
                            return false;
                        });
                    }
                    else
                    {
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end.gif");
                    }
                }
            });
        });


        $("#last").click(function() {

            str = last;
            is_first = false;
            is_last = true;
            $.ajax({

                url:path,
                async: false,
                type : 'POST',
                data: {
                    record_no : str,
                    id : rec_id,
                    flag : val
                } ,
                success: function(data){

                    $('#mydiv').html(data);

                    if(!is_first)
                    {
                        $("#first").attr("class","log_pagination first");
                        $("#prev").attr("class","log_pagination previous");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left.gif");

                    }
                    else
                    {
                        $("#first").attr("class","log_pagination first disabled");
                        $("#prev").attr("class","log_pagination previous disabled");
                        $("#first").attr("src", "/template/theme/bridge2call/images/grid/arrow_home_disabled.gif");
                        $("#prev").attr("src", "/template/theme/bridge2call/images/grid/arrow_left_disabled.gif");
                        $('#first').click(function() {
                            return false;
                        });
                        $('#prev').click(function() {
                            return false;
                        });

                    }
                    if(is_last)
                    {
                        $("#next").attr("class","log_pagination next disabled");
                        $("#last").attr("class","log_pagination last disabled");
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right_disabled.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end_disabled.gif");
                        $('#next').click(function() {
                            return false;
                        });
                        $('#last').click(function() {
                            return false;
                        });
                    }
                    else
                    {
                        $("#next").attr("src", "/template/theme/bridge2call/images/grid/arrow_right.gif");
                        $("#last").attr("src", "/template/theme/bridge2call/images/grid/arrow_end.gif");
                    }
                }
            });
        });
    }
});


