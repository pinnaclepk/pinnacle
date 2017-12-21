$(document).ready(function () {
    //alert("asdfa");
    $("form").bind("keydown", function (event) {
        //$('form').find('input').keydown(function(event){
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
    $("#frm").submit(function () {

        $(this).ajaxSubmit({
            success: function (responseText) {
                if (responseText != '')
                {
                    $("#loadpage").html(responseText);
                }
            }
        });
        return false;
    });


    $(".tablegridXajax").tablesorter();
    $('#AddRateFormula').click(function () {

        var rate = $('#RateFormula :selected').val();
        var formula = $('#Formula').val();
        $('#Formula').val(formula + rate);

    });
    $('#AddVariable').click(function () {
        var variable = '{-' + $('#Variable option:selected').val() + '-}';
        var body = $('#Body').val().toString();
        $('#Body').val(body + variable);
    });

//        $("#perPage").attr('onchange', 'getNextData(this.value)');
    $(".perPage").bind('click', function () {
        getNextData(this.id);
    });
    if ($(".grid_Active"))
        $(".grid_Active").attr("class", 'grid_active_button');
    if ($(".grid_Inactive"))
        $(".grid_Inactive").attr("class", 'grid_inactive_button');

    if ($(".errors"))
        $(".errors").tooltip();


    $("input[type=text].datepicker").each(function () {
        $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: '',
            showSecond: false,
            showButtonPanel: false
        });
    });

    $("input[type=text].date").each(function () {
        $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: '',
            showSecond: false,
            showButtonPanel: false
        });
    });


    $(".selectall").click(function () {
        var selectopt = $(this).attr('name');
        var options = selectopt.split("_");
        var checked_status = this.checked;

        $(".module_" + options[1]).each(function () {
            this.checked = checked_status;
        })
    });

    $("#Severity").change(function () {
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/misi/incident/incident/getnotification/',
            data: {
                "SeverityID": $('#Severity').val()
            },
            success: function (result)
            {
                if(result == 1)
                    $("#notification").attr("style", "display: block");
            }
        });
    });
    // Get the modal

// Get the image and insert it inside the modal - use its "alt" text as a caption
    if (document.getElementById('myImg') != 'undefined' && document.getElementById('myImg') != null)
    {
        var modal = document.getElementById('myModal');
        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

// Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    }
});

function getSponsors()
{
    $('#SponsorID').empty();
    $('#SponsorID').append('<option value="">Select</option>');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/admin/user/getsponsorlist/',
        data: {
            "UserTypeID": $('#UserTypeID').val()
        },
        success: function (result)
        {
            for (var key in result)
            {
                $('#SponsorID').append('<option value="' + key + '">' + result[key] + '</option>');
            }
        }
    });
}
function getClientSite()
{
    $('#SiteID').empty();
    $('#SiteID').append('<option value="">Select</option>');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/misi/timesheetapproval/timesheetapproval/getsite/',
        data: {
            "ClientID": $('#ClientID').val()
        },
        success: function (result)
        {

            for (var key in result)
            {
                $('#SiteID').append('<option value="' + result[key] + '">' + key + '</option>');
            }
        }
    });
}

function getLocation()
{
    $('#LocationID').empty();
    $('#LocationID').append('<option value="">Select</option>');
    if ($('#SiteID').val() != '') {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/misi/incident/incident/getlocation/',
            data: {
                "SiteID": $('#SiteID').val()
            },
            success: function (result)
            {
                for (var key in result)
                {
                    $('#LocationID').append('<option value="' + result[key] + '">' + key + '</option>');
                }
            }
        });
    }
}

function getLicence()
{
    $('#licencenumber').empty();

    if ($('#UserID').val() != '')
    {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/misi/incident/incident/getlicencenumber/',
            data: {
                "UserID": $('#UserID').val()
            },
            success: function (result)
            {
                $('#licencenumber').html(result);
            }
        });
    }
}

function getState()
{
    $('#CountryStateID').empty();
    $('#CountryStateID').append('<option value="">Select</option>');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/misi/state/state/getstate/',
        data: {
            "countryID": $('#CountryID').val()
        },
        success: function (result)
        {

            for (var key in result)
            {
                $('#CountryStateID').append('<option value="' + result[key] + '">' + key + '</option>');
            }
        }
    });
}

function getSiteGuard()
{
    $('#UserID').empty();
    //$('#UserID').append('<option value="">Select</option>');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/misi/timesheetapproval/timesheetapproval/getsiteguard/',
        data: {
            "SiteID": $('#SiteID').val()
        },
        success: function (result)
        {

            for (var key in result)
            {
                $('#UserID').append('<option value="' + result[key] + '">' + key + '</option>');
            }
        }
    });
}

function getReasons()
{
    $('#ReasonID').empty();
    $('#ReasonID').append('<option value="">Select</option>');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/misi/reason/reason/getreason/',
        data: {
            "ReasoncategoryID": $('#ReasoncategoryID').val()
        },
        success: function (result)
        {

            for (var key in result)
            {
                $('#ReasonID').append('<option value="' + key + '">' + result[key] + '</option>');
            }
        }
    });
}

function getNextData(perPage)
{
    var vstart = '';
    if (perPage != 'undefined' && perPage != '0')
        vstart = "/perPage/" + perPage;
    $(".loading_image").attr("style", "display:block");
    $.ajax({
        type: 'POST',
        url: moduleName + '/' + controllerName + '/' + actionName + vstart,
        async: false,
        data: postData,
        success: function (result)
        {
            if ($("#loadpage #grid").length) {
                $("#loadpage #grid").html(result);
            } else {
                $("#loadpage").html(result);
            }
            $(".loading_image").attr("style", "display:none");
        }
    });
}
function getNextData1(start)
{
    var vstart = '';
    var perpage = '';
    if ($(".currentPerPage").length > 0)
    {
        perpage = "/perPage/" + $(".currentPerPage").attr('id');
    } else {
        perpage = "/perPage/" + $(".perPage").first().attr('id');
    }
    if (start != 'undefined' && start > '0')
        vstart = "/start/" + start;

    $(".loading_image").attr("style", "display:block");
    $.ajax({
        type: 'POST',
        url: moduleName + '/' + controllerName + '/' + actionName + perpage + vstart,
        async: false,
        data: postData,
        success: function (result)
        {
            if ($("#loadpage #grid").length) {
                $("#loadpage #grid").html(result);
            } else {
                $("#loadpage").html(result);
            }
            $(".loading_image").attr("style", "display:none");
        }
    });
}
function getData(url)
{
    if (arguments[1] != "undefined" && arguments[1] == "back")
    {
        //url = lastURL[[lastURL.length - 2]];
        //lastURL.splice([lastURL.length - 2]);
        url = '/' + moduleName + '/' + controllerName + '/index';
    }
    //alert(url);

    var arr = url.split("/");
    var module = arr[1];
    var controller = arr[2];

    $(".loading_image").attr("style", "display:block");

    window.location.hash = controller;
    $.ajax({
        url: url,
        async: false,
        success: function (result)
        {
            $("#loadpage").html(result);
            $(".loading_image").attr("style", "display:none");

        }
    });
}

function getBackData(url)
{
    var arr = url.split("/");
    var module = arr[1];

    $(".loading_image").attr("style", "display:block");

    window.location.hash = module;
    url = "/misi" + url;
    $.ajax({
        url: url,
        async: false,
        success: function (result)
        {
            $("#loadpage").html(result);
            $(".loading_image").attr("style", "display:none");

        }
    });
}
function deleteData(url)
{

    if (confirm(msg_delete_confirm)) {
        var arr = url.split("/");
        var module = arr[1];
        $(".loading_image").attr("style", "display:block");
        window.location.hash = module;
        $.ajax({
            url: url,
            async: false,
            success: function (result)
            {
                $("#loadpage").html(result);
                $(".loading_image").attr("style", "display:none");
            }
        });
    }
}

function ActiveTab(activeid, module, requestUrl) {
    lastURL = '';
    $(".loading_image").attr("style", "display:block");
    window.location.hash = module;
    $.ajax({
        url: requestUrl,
        async: false,
        success: function (result)
        {
            $("#loadpage").html(result);
            $(".loading_image").attr("style", "display:none");
        }

    });
}

function enableBreakTime(obj)
{
    if (obj.checked)
    {
        $("#MealBreakTime").attr("disabled", false);
    }
    else
        $("#MealBreakTime").attr("disabled", true);
}

function enablestartendTime(obj)
{

    if (obj.value != '')
    {
        $("#StartTime").attr("disabled", false);
        $("#EndTime").attr("disabled", false);
    }
    else
        $("#StartTime").attr("disabled", true);
    $("#EndTime").attr("disabled", true);
}

function getTotalTime(obj)
{

    var endtime = $('#EndTime').datepicker('getDate').getTime();
    var starttime = $('#StartTime').datepicker('getDate').getTime();
    var breaktime = obj.value * 60 * 1000;
    var totaltime = endtime - starttime - breaktime;
    //var date = new Date(totaltime);
    var time = getYoutubeLikeToDisplay(totaltime);
    $("#TotalTime").attr("value", time);

}

function getYoutubeLikeToDisplay(millisec) {
    var seconds = (millisec / 1000).toFixed(0);
    var minutes = Math.floor(seconds / 60);
    var hours = "";
    if (minutes > 59) {
        hours = Math.floor(minutes / 60);
        hours = (hours >= 10) ? hours : "0" + hours;
        minutes = minutes - (hours * 60);
        minutes = (minutes >= 10) ? minutes : "0" + minutes;
    }

    seconds = Math.floor(seconds % 60);
    seconds = (seconds >= 10) ? seconds : "0" + seconds;
    if (hours != "") {
        return hours + ":" + minutes;
    }
    return minutes;
}