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
        let mainRow = document.querySelectorAll('.main-row');
        let filter = userInput.toLowerCase();

        for (i = 0; i < mainRow.length; i++) {
            if (!mainRow[i].innerHTML.toLowerCase().includes(filter)) {
                mainRow[i].style.display = "none";
            }
            else {
                mainRow[i].style.display = "";
            }
        }
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', app.init)
