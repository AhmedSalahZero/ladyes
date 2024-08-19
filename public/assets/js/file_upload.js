function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function readFavURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreviewFav').css('background-image', 'url('+e.target.result +')');
            $('#imagePreviewFav').hide();
            $('#imagePreviewFav').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function readDefaultBgURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreviewDefaultBg').css('background-image', 'url('+e.target.result +')');
            $('#imagePreviewDefaultBg').hide();
            $('#imagePreviewDefaultBg').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetPassURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imageresetPass').css('background-image', 'url('+e.target.result +')');
            $('#imageresetPass').hide();
            $('#imageresetPass').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
$("#imageUploadFav").change(function() {
    readFavURL(this);
});
$("#imageUploadDefaultBg").change(function() {
    readDefaultBgURL(this);
});
$("#imageResetPass").change(function() {
    resetPassURL(this);
});


$(function() {

    $(document).ready( function() {

        window.images = []; // UNPUT FILE NAME GLOBAL

//INPUT FILE UPLOADER CUSTOM SCRIPT BEGIN
        $('body').on('change', '.upload__input', function(event) {

            // console.log('change');
            let messages = $(this).closest('.upload').find('.count_img, .size_img, .file_types');
            $(messages).hide();

            let files = event.target.files;
            // console.log(files.length);

            let filename = $(this).attr('name').slice(0, -2);

            let names2 = window.images[filename];
            let names = [];
            // console.log('NAMES2 !!!!! = ' + names2);
            if(names2)
            {
                names = names2;
            }

            let max_count = $(this).data('maxCount');
            // console.log('max_count' + max_count);

            for (var i = 0; i < files.length; i++) {

                let file = files[i];

                //count
                names.push(file.size);
                console.log('names = ' + names);
                console.log('FILE = ' + names.length);


                if (names.length == max_count) {
                    $(this).closest('.upload').find('.count_img').show();
                    $(this).closest('.upload').find('.count_img_var').html(max_count);
                    $(this).closest('.upload').find('.upload__btn').hide();
                }
                if (names.length > max_count) {
                    names.pop();
                    return false;
                }
                window.images[filename] = names;

                //type
                var fileType = file.type;
                // console.log(fileType);
                if (fileType == 'image/png' || fileType == 'image/jpeg' || fileType == 'video/mp4'){

                }
                else{
                    $(this).closest('.upload').find('.file_types').show();
                    return false;
                }

                if (fileType == 'video/mp4'){
                    var max_size = 3;
                }
                else{
                    var max_size = 3;
                }

                //size
                var totalBytes = file.size;

                //MB into bites
                var max_bites = max_size * 1024 * 1024;
                // console.log(max_bites);
                if(totalBytes > max_bites){
                    $(this).closest('.upload').find('.size_img').show();
                    $(this).closest('.upload').find('.size_img_var').html(max_size + 'MB');
                    return false;
                }

                var picBtn = $(this).closest('.upload').find('.upload__btn');
                // console.log('picBtn' + this);

                var picReader = new FileReader();
                picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var picSize = event.total;
                    var picCreate = $("<div class='upload__item' uk-lightbox ><a href='"+picFile.result+"' data-type='image' style='display:block'><img src='" + picFile.result + "'" + " class='upload__img'/></a><a data-id='" + picSize + "' class='upload__del'></a></div>");
                    $(picCreate).insertBefore(picBtn);
                });
                // console.log(file);
                picReader.readAsDataURL(file);
            }
            // console.log(names);
        });

        $('body').on('click', '.upload__del', function() {

            $(this).closest('.upload').find('.upload__btn').show();

            let filename = $(this).closest('.upload').find('.upload__input').attr('name').slice(0, -2);
            // console.log('FILENAME = ' + filename);

            let names = window.images[filename];

            let messages = $(this).closest('.upload').find('.count_img, .size_img, .file_types');
            $(messages).hide();

            $(this).closest('.upload__item').remove();


            var removeItem = $(this).attr('data-id');
            var yet = names.indexOf(removeItem);
            names.splice(yet, 1);

        });
        $('.select_type_damage').change();
        var countItems = 0;
        if(countItems > 0)
        {
            $('#damagelist .default').fadeOut();
            $('#submit-form').show();
        }else {
            $('#damagelist .default').fadeIn();
            $('#submit-form').hide();
        }
        configSelect();
    });/*$(document).ready( function()*/
});
