$('#postStudent').submit(function(event) {
    event.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var data = form.serializeArray().reduce(function(m,o){
        var key = o.name.split('[').pop().split(']')[0];
        m[key] = o.value;
        return m;
    }, {});
    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(data)
    }).done(function() {
        location.reload();
    }).fail(function() {
        alert('Something went wrong');
    });
});
