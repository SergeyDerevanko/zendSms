$.fn.iCrop = function(options){
    var object_id;

    var settings = $.extend( {
        url_load: '',
        url_crop: '',
        start_load : function(e, data){},
        error_load : function(e, data){},
        end_load : function(data){},
        start_load_crop : function(){},
        error_load_crop : function(){},
        end_load_crop : function(data){},
        aspectRatio : 1,
        boxWidth : 300,
        boxHeight : 300
    }, options);

    var $this = $(this),
        $load_btn = $this.find('#load-btn'),
        $block_crop_img = $this.find('#block_crop_img'),
        $t_img = $block_crop_img.find('#t_img'),
        $crop_img = $block_crop_img.find('#crop_img'),
        $default_img = $this.find('#default-img'),
        $crop_edit_btn = $this.find('#crop-edit-btn'),
        $crop_main_block = $this.find('#crop-main-block'),
        $save_btn = $this.find('#save-btn'),
        $input = $this.find('[name="object_id"]');

    var cropParams;

    var jCropApi = $.Jcrop($crop_img,{
        aspectRatio : settings.aspectRatio,
        bgOpacity  : .3,
        boxWidth : settings.boxWidth,
        boxHeight : settings.boxHeight,
        touchSupport: true,
        shade: true,
        allowSelect: false,

        onSelect : function (c) {
            cropParams = c;
        }
    });

    $block_crop_img.hide();
    $save_btn.hide();
    $t_img.hide();

    if($default_img.attr('src') == '') $default_img.hide();
    if($crop_img.attr('src') == '') {
        $crop_img.hide();
    } else {
        object_id = $input.val();
        $this.find('.jcrop-holder').show();
        $save_btn.show();
        jCropApi.setImage($crop_img.attr('src'), function (){
            this.setSelect([0,0].concat(this.getBounds()));
        });
    }

    $crop_edit_btn.on('click', function(){
        $crop_main_block.slideUp('slow', function(){
            $block_crop_img.slideDown();
        });
    });


    $load_btn.fileupload({
        url : settings.url_load,
        dataType : 'json',
        singleFileUploads : true,
        send: settings.start_load,
        done : function(e, data){
            if(!data.result.success) return settings.error_load(e, data);
            $t_img.attr('src', data.result.img_url);
            $t_img.show();
            $this.waitForImages({
                finished: function(){
                    object_id = data.result.object_id;
                    $t_img.hide();
                    $crop_img.attr('src', data.result.img_url);
                    $this.find('.jcrop-holder').show();
                    $crop_img.show();
                    $save_btn.show();
                    $input.val(object_id);
                    jCropApi.setImage(data.result.img_url, function (){
                        this.setSelect([0,0].concat(this.getBounds()));
                    });
                    settings.end_load(data.result);
                },
                waitForAll: true
            });
        }
    });



    $save_btn.on('click', function(){
        cropParams['object_id'] = object_id;
        settings.start_load_crop();
        $.ajax({
            url : settings.url_crop,
            type : 'POST',
            data : cropParams,
            dataType : 'json',
            success : function ( response ) {
                if(response.success){
                    $t_img.attr('src', response.img_url);
                    $t_img.show();
                    $this.waitForImages({
                        finished: function(){
                            $t_img.hide();
                            $default_img.attr('src', response.img_url);
                            $default_img.show();
                            $block_crop_img.slideUp('slow', function(){
                                $crop_main_block.slideDown();
                            });
                            settings.end_load_crop(response);
                        },
                        waitForAll: true
                    });

                } else {
                    settings.error_load_crop(response);
                }
            },
            error : function (error) {
                settings.error_load_crop(error);
            }
        });
        return false;
    });
}