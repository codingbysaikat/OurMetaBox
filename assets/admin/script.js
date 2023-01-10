var frame;
var attaactment;
;(function($){
    $(document).ready(function(){
        if($('#image_url').val()){
            var image_url = $('#image_url').val();
            $('.attach_image').append( '<img id="att-image" src="'+image_url+'" alt="" style="max-width:100%;"/>' );

        }

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
                attaactment = frame.state().get('selection').first().toJSON();
               if($('#att-image')){
                $('#att-image').remove();
               }
               
                
                $('#image_id').val(attaactment.id);
                $('#image_url').val(attaactment.url);
                $('.attach_image').append( '<img id="att-image" src="'+attaactment.url+'" alt="" style="max-width:100%;"/>' );

            });
            frame.open();
    
            
            return false;
            
    
    
        });

    });
   


})(jQuery)