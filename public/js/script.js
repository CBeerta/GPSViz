/* Author: 

*/

$(document).ready(function() {
    $("div#info_snippet").each(function(index) 
    {
        var snippet = $(this);
        var name = $(this).attr("name");
        if (name) 
        {
            $.get('/?/main/ajax/' + name, function(data) {
                snippet.html(data);
            });
        }
    });
});


