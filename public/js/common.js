let _$ = $(document);

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});  

_$.ajaxError(function (event, jqxhr, settings, thrownError) {
    if (jqxhr.status === 401) {
        console.log(401);
        document.location.href = route('login');
    } else if (jqxhr.status === 419) {
        console.log(419);
        errorMessage('Session expired');
    }
});

_$.ready(function(){ 
     
    if (typeof $(".datepicker") !== 'undefined' && $(".datepicker").datepicker) {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+15"
        });
    }
    
    
    $('[data-toggle="tooltip"]').tooltip();

    if (typeof $(".select2") !== 'undefined' && $(".select2").select2) {
        $(".select2").select2({
            placeholder: "Select Option",
            allowClear: true
        });    
    }

    $('.datatable').on('click', '.btn-status', function (e) {
        e.preventDefault();  

        $datatable = $(this).parents('.datatable');
        var url= $(this).attr('href');

        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure! You want to update status of this record?',
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $datatable.find("tbody").LoadingOverlay("show");
                    $.ajax({
                        type: "get",
                        url: url,
                        dataType: "json",
                        complete:function (res) {
                            $datatable.find("tbody").LoadingOverlay("hide");
                            var result = JSON.parse(res.responseText);
                            //var result = j.result;
                            if(result.success){
                                reloadDatatable($datatable);
                                successMessage(result.message);
                            }else{
                                errorMessage(result.message);
                            }
                        },
                          error: function (request, status, error) {
                              $datatable.find("tbody").LoadingOverlay("hide");                     
                              var result = request.responseJSON.result;
                              var err = JSON.parse(request.responseText);
                              if(status == 401){
                                errorMessage(result.message);                  
                            }else{
                                errorMessage(err.message);
                            }                    
                          } 
                    });                                                                         
                },
                cancel: function () { },
            }
        });

        return false;

    });

    $('.datatable').on('click', '.btn-delete', function (e) 
    {
        e.preventDefault();  
        $datatable = $(this).parents('.datatable');
        var url = $(this).attr('href');
        var type = "delete";

        if ($(this).attr('type') != undefined) {
            type = $(this).attr('type');
        }

        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure! You want remove this record',
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $datatable.find("tbody").LoadingOverlay("show");
                    $.ajax({
                        type: type,
                        url: url,
                        dataType: "json",
                        complete:function (res) {
                            $datatable.find("tbody").LoadingOverlay("hide");
                            var result = JSON.parse(res.responseText);
                            //var result = j.result;
                            if(result.success){
                                reloadDatatable($datatable);
                                successMessage(result.message);
                            }else{
                                errorMessage(result.message);
                            }
                        },
                          error: function (request, status, error) {
                              $datatable.find("tbody").LoadingOverlay("hide");                     
                              var result = request.responseJSON.result;
                              var err = JSON.parse(request.responseText);
                              if(status == 401){
                                errorMessage(result.message);                  
                            }else{
                                errorMessage(err.message);
                            }                    
                          } 
                    });                                                                         
                },
                cancel: function () { },
            }
        });

        return false;
    });    
    
});

/**
 * Create Ajax Datatables
 * @param url
 * @param columns
 * @param index_field
 * @param ordering
 * @param pageLength
 * @param permitOrder
 */
function create_datatables (url, columns, datatable_class = 'datatable', index_field = true, ordering = [], pageLength=10, permitOrder = true, exportData = false, exportButtons = ['csv', 'excel', 'pdf', 'print'])
{
    if (index_field) {
        $('.'+datatable_class+' thead tr').prepend("<th>#</th>");
        $('.'+datatable_class+' tfoot tr').prepend("<th>#</th>");

        columns.unshift({name:'index', data: 'index', width: '2%', className: 'text-center', orderable: false, searchable: false});
    }
     
    var options = {
        // search: {return: true},
        oLanguage: { sProcessing: '<img src="'+ Ziggy.url +'/images/bx_loader.gif">' },
        processing: true,
        serverSide: true,
        ordering: permitOrder,
        responsive: true,
        pageLength: pageLength,
        bLengthChange: (pageLength>0)?(pageLength==30?false:true):false,
        paging: (pageLength>0)?true:false,
        info: (pageLength>0)?(pageLength==30?false:true):false,
        ajax: {
            url: url,
            type:'POST',
        },
        columns: columns,
        order: ordering,
        drawCallback: function ( settings ) {
            var api = this.api();

            if (index_field) {
                api.column(0).nodes().each(function (cell, i) {
                    var index = (i+1) + (t.page.info().page * t.page.info().length);
                    cell.innerHTML = index;
                });
            }
        }
    };
    
    if (exportData) {
        options.dom = 'Bfrtip';
        options.buttons = exportButtons;
    }

    var t = $('.'+datatable_class).DataTable(options);
    
    return t;
}

/**
 * Show Dropdown
 * @param el
 * @param show
 */
function loadDropdown(el, show = false) {
    $url = el.attr('data-url');
    $ddName = el.attr('name');
    $selected = el.attr('data-selectedid');
    $otherIdName = (el.attr('data-otheridname')) ? el.attr('data-otheridname') : '';
    $otherIdValue = (el.attr('data-otheridvalue')) ? el.attr('data-otheridvalue') : '';

    $id = $selected;
    $ddName = $ddName;

    var _self = $('#select2-' + $ddName + '-container');
    if (_self[0] == undefined){
        _self = $('#' + $ddName);
    }

    loadingOverlay(_self, show);
    $.ajax({
        type: "POST",
        url: $url,
        data: {
            'id': $id,
            'ddName': $ddName,
            'otherIdValue': $otherIdValue
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            $('select[name=' + data.request.ddName + ']').html(data.options);
            if ($id > 0) {
                $('select[name=' + data.request.ddName + ']').change();
            }
        },
        error: function (data, textStatus, jqXHR) {
            //process error msg
        },
        complete:function (res) {
            stopOverlay(_self, show);
        }
    });
}

/**
 * Show Ajax Error Message
 * @param response
 */
function showAjaxErrorMessage(response, form = false)
{
    let responseJson = JSON.parse(response.responseText);
    let errors = responseJson.errors;
    
    if (form) {
        if (errors !== undefined) {
            Object.keys(errors).forEach(function (item) {
                for (let value of errors[item]) {                    
                    $('[name=' + item + ']').parent('.form-group').find(".text-danger").text(value);
                    $('#' + item + '-error').text(value);
                    $('[name=' + item + ']').addClass('is-invalid');
                }
            });
        }
    } 
    if (errors !== undefined) {
        Object.keys(errors).forEach(function (item) {
            for (let value of errors[item]) {
                errorMessage(value);
            }
        });
    } else if (responseJson.message !== undefined) {
        errorMessage(responseJson.message);
    }
    
}

/**
 * Loading overlay js
 * @param _ele
 */
let loadingOverlay = (_ele, show = true) => {
    if (show) {
        _ele.LoadingOverlay('show');
    }    
}

/**
 * Stopping overlay js
 * @param _ele
 */
let stopOverlay = (_ele, hide = true) => {
    if (hide) {
        _ele.LoadingOverlay('hide');
    }
}

$.fn.serializeFiles = function () {
    let form = $(this),
        formData = new FormData(),
        formParams = form.serializeArray();

    $.each(form.find('input[type="file"]'), function (i, tag) {
        $.each($(tag)[0].files, function (i, file) {
            formData.append(tag.name, file);
        });
    });

    $.each(formParams, function (i, val) {
        formData.append(val.name, val.value);
    });

    return formData;
};

/**
 * Reload Datatable
 * @param _ele
 */
function reloadDatatable (tableId = '#datatable') {
    let _datatable = $(tableId);
    let reloadDatatable = _datatable.dataTable({ bRetrieve : true });
    if(reloadDatatable != ""){
        reloadDatatable.fnDraw(); 
    }
}

/**
 * Generate Random Number
 * @param length
 */
function generateRandomNumber (length) {
    if(!length) { length = 16; }
    //var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var chars = "1234567890";
    var result="";

    for (var i = length; i > 0; --i)
        result += chars[Math.round(Math.random() * (chars.length - 1))]
    return result
}

_$.ready(function(){               
    $('.tooltips').tooltip();
    $('[data-toggle="tooltip"]').tooltip();
});

 
/**
 * Show Success Message
 * @param message
 * @param title
 */
function generalMessage(response = false) {
    if (response.success) {
        successMessage(response.message);
    }
    else {
        errorMessage(response.message);
    }
}

/**
 * Show Success Message
 * @param message
 * @param title
 */
function successMessage(message, title)
{
    if (!title) title = "Success!";
    toastr.remove();
    toastr.success(message, title, {
        closeButton: true,
        timeOut: 4000,
        progressBar: true,
        newestOnTop: true
    }); 
}

/**
 * Show Error Message
 * @param message
 * @param title
 */
function errorMessage(message, title)
{
    if (!title) title = "Error!";
    toastr.remove();
    toastr.error(message, title, {
        closeButton: true,
        timeOut: 4000,
        progressBar: true,
        newestOnTop: true
    }); 
}



/**
 * Accepts either a URL or querystring and returns an object associating 
 * each querystring parameter to its value. 
 *
 * Returns an empty object if no querystring parameters found.
 */
function getUrlParams(urlOrQueryString) {
    if ((i = urlOrQueryString.indexOf('?')) >= 0) {
      const queryString = urlOrQueryString.substring(i+1);
      if (queryString) {
        return _mapUrlParams(queryString);
      } 
    }
    
    return {};
  }
  
  /**
   * Helper function for `getUrlParams()`
   * Builds the querystring parameter to value object map.
   *
   * @param queryString {string} - The full querystring, without the leading '?'.
   */
  function _mapUrlParams(queryString) {
    return queryString    
      .split('&') 
      .map(function(keyValueString) { return keyValueString.split('=') })
      .reduce(function(urlParams, [key, value]) {
        if (Number.isInteger(parseInt(value)) && parseInt(value) == value) {
          urlParams[key] = parseInt(value);
        } else {
          urlParams[key] = decodeURI(value);
        }
        return urlParams;
      }, {});
  }

  function datatableExportAction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function(e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 100000;
        dt.one('preDraw', function(e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function(e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}


function ajaxCall(formId = false, url, method = "GET", data = null, dataType = 'json', onSuccessCallback = false, onErrorCallback = false) {
    
    $('.error-message').remove();
    $('.error').next('div').remove();
    $('.valid').next('div').remove();
    $('.error').removeClass('error');

    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: dataType,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $(".btn-submit").attr("disabled", false);

            if (response.code == 422) {
                validationErrors(formId, response);
                return false;
            }

            if (onSuccessCallback) {
                onSuccessCallback(response);
                return false;
            } else {
                generalMessage(response);
            }
        },
        error: function (xhr, status, error) {
            $(".btn-submit").attr("disabled", false);
            if (onErrorCallback) {
                onErrorCallback(error);
            } else {
                generalMessage(error);
            }
        }
    });
}

function validationErrors(formId, response) {
    $('.error-message').remove();
    $('.error').next('div').remove();
    $('.valid').next('div').remove();
    $('.error').removeClass('error');

    var errorString = '';
    var index = 0;

    $.each( response.data, function( key, value) {

        if (index === 0) {
            toastr.error(value, "Error");
            index++;
        }
       

        errorString += value + '<br>';
        $("#"+formId+" #" + key).addClass('error');
        $("#"+formId+" #" + key).next('div').remove();
        $("#"+formId+" #" + key).after('<div class="error-message">'+value+'</div>');
        
    });
    toastr.warning(response.message, "Error");
    $(".btn-submit").attr("disabled", false);
}



