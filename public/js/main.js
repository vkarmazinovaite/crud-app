$('#postStudent').submit(function(event) {
    event.preventDefault();
    var form = $(this);
    var url = form.attr('action'); //get submit url [replace url here if desired]
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize().json()
    }).done(function() {
        location.reload();
    }).fail(function() {
        alert('Something went wrong');
    });
});
