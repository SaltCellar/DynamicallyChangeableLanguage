function setLang(language,target = false) {

    if(jQuery.type(language) !== 'string') {
        console.warn('Invalid language parameter [0].');
        return;
    }

    let TargetId = "";
    let LanguageRequest = new Object();

    if(target !== false && jQuery.type(target) === 'string') {
        TargetId = target + " ";
    }

    $(TargetId + '[data-lang]').each(function() {
        let _data = ($(this).data('lang')).split(".");
        if(_data.length == 2) {

            if(!(_data[0] in LanguageRequest)) {
                LanguageRequest[_data[0]] = [];
            }

            LanguageRequest[_data[0]].push(_data[1]);

        }
    });

    // AJAX SECTION

    executeAjax((data) => {
        if(data.status) {

            // HANDLE THE RESULT

            $.each(data.data, (k,v) => {
                $('[data-lang="'+k+'"]').html(v);
            });

            // END OF RESULT HANDLING


        } else {
            console.error(data.error);
        }
    },language,LanguageRequest);

    // END OF AJAX SECTION

}