function executeAjax (
    _callBack,
    _info   = false,
    _data   = false,
    _callBackError = DefaultAjaxCallbackError,
) {
    $.ajax({
        url: 'ajaxController.php',
        type: 'POST',
        data: function(){
            let data = new FormData();
            data.append('INFO',JSON.stringify(_info));
            data.append('DATA',JSON.stringify(_data));
            return data;
        }(),
        success: (data) => {
            _callBack(data);
        },
        error: (data) => {
            _callBackError(data);
        },
        cache: false,
        contentType: false,
        processData: false
    });
}

function DefaultAjaxCallbackError(data) {
    console.error('Ajax Error:');
    console.error(data)
}