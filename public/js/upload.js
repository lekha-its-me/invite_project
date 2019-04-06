var fileobj;
function upload_file(e) {
    e.preventDefault();
    if(e.dataTransfer.files.length == 1)
    {
        ajax_file_upload(e.dataTransfer.files);
    } else {
        alert('Можно загрузить только 1 файл');
    }
}

function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function() {
        files = document.getElementById('selectfile').files;
        ajax_file_upload(files);
    };
}

function ajax_file_upload(file_obj) {
    if(file_obj != undefined) {
        var form_data = new FormData();
        for(i=0; i<file_obj.length; i++) {
            form_data.append('file[]', file_obj[i]);
        }
        $.ajax({
            type: 'POST',
            url: '/upload/get',
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
                alert(response);
                $('#selectfile').val('');
            }
        });
    }
}
