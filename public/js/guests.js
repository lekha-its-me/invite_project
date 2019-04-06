$('#select_all').click(function(e){
    var table= $(e.target).closest('table');
    $('td input:checkbox',table).prop('checked',this.checked);
});

$('#sendInvite').click(function (e) {
    var subject = $('#subject').val();
    var body = $('#letterBody').val();


    if(subject!=''&&body!=''){
        var recipients = [];
        $.each($("input[name='guests']:checked"), function(){
            recipients.push($(this).val());
        });
        if(recipients.length > 0){
            $.ajax({
                type: 'POST',
                url: '/guests/send',
                data: 'subject=' + subject + '&letterBody=' + body + '&recipients=' + recipients,
                success:function(response) {
                    if(response == 'ok'){
                        $('#subject').val('');
                        $('#letterBody').val('');
                        alert('Успешно отправлено');
                    }
                }
            });
        } else {
            alert('Выберите хотя бы одного получателя');
        }

    } else {
        alert('Заполните тему и текст письма');
    }
});
