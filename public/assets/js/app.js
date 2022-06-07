var app = {

    init: () => {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        $('#download-pdf').on('click', app.downloadPdf);
    },
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', app.init)
