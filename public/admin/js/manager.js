$(function(){
    $('body').on('click', '.tr-btn-delete', function(){
        var $this = $(this),
            $block = $this.closest('tr');

        $(this).parent().onLoadBlock();
        $.ajax({type: 'POST',
            datatype:"json",
            url: publicUrl+ $this.attr('data-url'),
            data: {'id' : $block.attr('data-id')},
            success: function(data){
                if(data.success){
                    $block.remove();
                }
            },
            complete: function(){
                $block.offLoadAll();
            }
        });
        return false;
    }) ;


});