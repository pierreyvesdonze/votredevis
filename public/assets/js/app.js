var app = {

    init: () => {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        $('#download-pdf').on('click', app.downloadPdf);
        $('#estimate-line-form-delete-submit').on('click', app.deleteEstimateLine)
    },

    deleteEstimateLine: (e) => {
        e.preventDefault();
        
        let estimateId = $('.select-estimate-line').data('estimate');
        console.log(estimateId);
        let lineToDeleteId = $('#estimate-line-delete-form').val()
  
        $.ajax(
            {
                url: Routing.generate('estimate_line_delete'),
                method: "POST",
                dataType: "json",
                data: lineToDeleteId
            }).done(function (response) {
                if (null !== response) {
                    console.log('ok : ' + response);
                    location.reload();
                } else {
                    console.log('Problème');
                }
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', app.init)
