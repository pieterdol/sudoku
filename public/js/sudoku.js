submitSudoku = function(data) {
    var resultContainer = $('#resultContainer');
    $.ajax({
        type        : "POST",
        dataType    : "json",
        url         : "index.php",
        data        : data,
        success: function(data){
            if (typeof resultContainer === 'undefined') {
                return false;
            }
            
            // Zet bericht in result container
            var message = (data.message) ? data.message : "Something went wrong...";
            resultContainer.html(message);
            
            // Voeg juiste css class toe op basis van status
            var status = (typeof data.status !== 'undefined' && data.status === true) ? true : false;
            var addStatusClass = (status === true) ? 'success' : 'error';
            var removeStatusClass = (status === true) ? 'error' : 'success';
            addClassToElement(resultContainer, addStatusClass, removeStatusClass);
            
            if (resultContainer.is(":visible") === false) {
                resultContainer.toggle();
            }
        },
        error: function(){
            addClassToElement(resultContainer, 'error', 'success');
            resultContainer.html('Something went wrong...');
            return false;
        }
    });
    return true;
};

addClassToElement = function(element, classToAdd, classToRemove) {
    if (typeof element === 'undefined') {
        return false;
    }
    if (typeof classToAdd !== 'undefined' && element.hasClass(classToAdd) === false) {
        element.addClass(classToAdd);
    }
    if (typeof classToRemove !== 'undefined' && element.hasClass(classToRemove) === true) {
        element.removeClass(classToRemove);
    }
    return true;
};

$(document).ready(function() {	
    $('#sudokuForm').on("submit", function (e) {
        e.preventDefault();
        submitSudoku($(this).serialize());
    });
});
