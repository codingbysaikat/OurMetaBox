var frame;
;(function($){
    $(document).ready(function(){

        $('#upload_image').on('click',function(){
            if(frame){
                frame.open();
                return false;
            }
            frame = wp.media({
                title : 'Upload Image',
                button:{
                    text : 'Select Image'
                },
                multiple: false,
    
    
            });
            frame.on( 'select', function() {
                var attaactment = frame.state().get('selection').first().toJSON();
                $('#image_id').val(attaactment.id);
                $('#image_url').val(attaactment.url);
                $('.attach_image').append( '<img src="'+attaactment.url+'" alt="" style="max-width:100%;"/>' );

            });
            frame.open();
    
            
            return false;
            
    
    
        });

    });
   


})(jQuery)