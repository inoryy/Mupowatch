$('.vote').live('click', function() {
    var parent = $(this).parent();
    var action = $(this).data('vote');
    var id = $(this).data('id');
    
    parent.html('<img src="/bundles/inorimupowatch/images/ajax-loader.gif" />');
    
    $.getJSON(ajax_vote, { action : action, id : id}, function(data) {
       if (data.status) {
           var color = data.result > 0 ? 'green' : 'red';
           if (data.status == 'success') {
               parent.html('<span class="vote_result" style="color:'+ color +'">'+ data.result +'</span>');
           } else if (data.status == 'fail') {
               parent.html('<span class="vote_result" style="color:'+ color +'">'+ data.result +'</span>');
           } 
       } else {
           alert('Teekis viga, proovige uuesti');
           window.location.href = window.location.href;
       } 
    });
});