$(document).ready(function(){
        
        $(".formcadastrar").on("submit", function(e){
            e.preventDefault();

            form= $(this);
            url = form.attr('action');
            form_data = form.serialize();

            $.ajax({
                url: url,
                method: 'post',
                data: form_data,
                dataType: "JSON",
                success: function(response){
                    console.log(response);
                    
                }
                
            });
        
        })
})