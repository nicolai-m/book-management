

$(document).ready(function(){
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
        $("#wait-bg").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
        $("#wait-bg").css("display", "none");
    });
});


function delBook(id) {
    $.ajax({
        type: "POST",
        url: "/ajax/del-book",
        data: {
            image: id
        },
        success: function(result) {

            if(result.valueOf(result.successful)) {

            }
        },
    });
}

function applyDetails(isbn) {
    var title = $("#"+isbn+"_title").text(),
        isbn_13 = $("#"+isbn+"_isbn").text(),
        author = $("#"+isbn+"_author").text();

    if(title.length > 0) {
        $('#book_form_title').val(title);
    }

    if(isbn_13.length > 0) {
        $('#book_form_isbn').val(isbn_13);
    }

    if(author.length > 0) {
        $('#book_form_author').val(author);
    }

    $('#openlibrary-result').modal('hide');
}

function getDataByIsbn() {
    var bookIsbn = $('#book_form_isbn').val();

    if(bookIsbn.length > 0) {
        $.ajax({

            type: "POST",
            url: "/ajax/book-infos-by-isbn",
            data: {
                isbn: bookIsbn
            },
            success: function(result) {

                $('#modal-box').html(result)
                $('#openlibrary-result').modal('show');

            },
        });
    }
}

function getDataByTitle() {
        var bookTitle = $('#book_form_title').val();

        $.ajax({
        type: "POST",
        url: "/ajax/book-infos-by-title",
        data: {
            image: bookTitle
        },
        success: function(result) {

        },
    });
}