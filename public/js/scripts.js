$(document).ready(function(){
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
        $("#wait-bg").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
        $("#wait-bg").css("display", "none");
    });

    $("#search input").on("input", function(){
        var $searchVal = $( this ).val();
        $.ajax({
            type: "POST",
            url: "/search/autocomplete",
            data: {
                search_value: $searchVal
            },
            success: function(result) {
                var $searchResult = result;
                if ($searchResult.length === 0) {
                    $("#search-result").remove();
                } else {
                    $("#search-result").remove();
                    $("#search").append( $searchResult );
                }
            },
        });
    });
});


function delBook(isbn) {
    var url = "/";

    $.ajax({
        type: "POST",
        url: "/ajax/book-delete",
        data: {
            isbn: isbn
        },
        success: function(result) {
            $('#modal-box').html(result)
            $('#book-deleted').modal('show');
            setTimeout(
                function()
                {
                    $(location).attr('href',url);
                }, 5000);
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

    if(bookTitle.length > 0) {
        $.ajax({

            type: "POST",
            url: "/ajax/book-infos-by-title",
            data: {
                title: bookTitle
            },
            success: function(result) {

                $('#modal-box').html(result)
                $('#openlibrary-result').modal('show');

            },
        });
    }
}