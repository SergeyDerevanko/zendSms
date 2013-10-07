$(function(){



});

var img;
//Rotation bg-image
function changeBgPosition(){
    var pos;
    if(icount<8){
        pos=icount*56;
        icount++;

    }else{
        pos=0;
        icount=0;
    }
    img.css('background-position','-'+pos+'px 0');
}

$.fn.extend({

    onLoadBlock:function(){

        $(this).prepend('<div class="load-block">' +
            '<div id="loader">' +
            '<div class="chefHat">' +
            '<span class="hatShape left"></span>' +
            '<span class="hatShape midl"></span> ' +
            '<span class="hatShape right"></span>' +
            '<span class="hatRec"></span>' +
            '<span class="hatRec btm"></span>' +
            '</div>' +
            '</div>');
        var block = $(this).find('div.load-block');
        img = block.find('div.img_upload, div.time_upload');
        //changeBg
        width = $(this).width();
        height = $(this).height();
        block.css({
            'backgroundColor':'#FFF',
            'position':'absolute',
            'opacity':'0'
        });

        img.css('margin-top', parseInt(height/2)+'px');
        //img.css('margin-left', parseInt(width/2-img.width()/2)+'px');
        img.css('margin-left', parseInt(width/2)+'px');


        block.css('width', width + 'px');
        block.css('z-index', 999999);
        block.css('height', height + 'px');
        block.animate({opacity: 0.9},500);
        return $(this);
    },
    offLoadAll: function() {

        var block = $(this).find('div.load-block');
        block.animate({opacity: 0}, 500, function() {
            $(this).remove()
        });
    },
    onLoadBtn: function (){
        _this = $(this);
        var width = _this.width();
        var height = _this.height();

        _this.prepend('<div class="load-block"><div class="img_upload_mini"></div></div>');

        var block = _this.find('div.load-block');
        var img = block.find('div.img_upload_mini');

        img.css('width', width + 'px');
        img.css('height', height + 'px');
        img.css('float', 'left');
        img.css('background-position', parseInt(width/2-45.5)+'px '+parseInt(height/2-12.5)+'px');
        block.css({
            'position' : 'absolute',
            'background-color' : '#fff',
            'z-index' : '9',
            'width': width + 'px',
            'height': height + 'px',
            'opacity' : 0
        })

        block.animate({opacity: 0.9},500);
        return $(this);
    }


});



$.fn.extend({
    //add 'error-field' class
    setErrorForInput:function(){
        return $(this).each(function() { $(this).addClass('error-field'); });
    },

    clearBlockError:function(){
        var er=$('.error-field');
        er.removeClass('error-field');
        if(er.attr('class')=='') er.removeAttr('class');
        $('[class*="error-block"]').text('');
        return $(this);
    },

    addFormErrorAlert:function(text){
        var $this = $(this);

        $this.prepend('<div class="alert alert-block alert-danger fade in">' +

            '<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>' +
            text + '</div>');

        setTimeout(function(){
            $this.find('.alert').alert('close');
        }, 3000);

        return $this;
    },
    clearFormAlert:function(){
        $(this).find('.alert').hide('blind', function(){
            $(this).remove();
        });
        return $(this);
    }

});


$(function(){
    $('tr .conf-btn').popover({
        html: true,
        placement: 'top'
    });
})

