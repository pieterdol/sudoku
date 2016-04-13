submitSudoku = function(data) {
    $.ajax({
        type        : "POST",
        dataType    : "json",
        url         : "index.php",
        data        : data,
        success: function(data){
            if (typeof data.status === 'undefined' || data.status === false) {
                var message = (data.message) ? data.message : "Something went wrong... :(";
            } else {
                var message = (data.message) ? data.message : "Goed bezig";
            }
            $('#resultContainer').html(message);
            if ($('#resultContainer').is(":visible") === false) {
                $('#resultContainer').toggle();
            }
        },
        error: function(data){
            console.log('something went wrong');
        }
    });
};

$(document).ready(function() {	
    $('#sudokuForm').on("submit", function (e) {
        e.preventDefault();
        submitSudoku($(this).serialize());
    });
});
