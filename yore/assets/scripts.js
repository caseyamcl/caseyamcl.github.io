$(document).ready(function() {

    $('.article-browser-toggle').click(function() {
        $('#yore-article-browser').toggleClass('navmode');
    });

    $('.article-list a').click(function(e) {
        e.preventDefault();

        var self = $(this);

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            error: function(err) { alert("Something went wrong.... Go tell Casey."); console.log(err); },
            success: function(data) {

                // active-article-title
                $('#active-article-title').html(data.title);

                // active-article-datetime
                $('#active-article-datetime').prop('datetime', data.timestamp.date);
                $('#active-article-datetime').text(data.timestamp.date.split(' ')[0]);

                // active-article-body
                $('#active-article-body').html(data.content);

                self.parents('li').addClass('active').siblings('li').removeClass('active');
                self.parents('#yore-article-browser').removeClass('navmode');
            }
        });
    });

});