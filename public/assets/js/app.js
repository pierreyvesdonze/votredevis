var app = {

    init: () => {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        document.addEventListener('keyup', app.searchEstimate)
    },

    searchEstimate: (evt) => {
        evt.preventDefault();
        
        let userInput = document.querySelector('.search-input').value;
        let estimateRow = document.querySelectorAll('.estimate-row');
        let filter = userInput.toLowerCase();

        for (i = 0; i < estimateRow.length; i++) {
            if (!estimateRow[i].innerHTML.toLowerCase().includes(filter)) {
                estimateRow[i].style.display = "none";
            }
            else {
                estimateRow[i].style.display = "";
            }
        }
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', app.init)
