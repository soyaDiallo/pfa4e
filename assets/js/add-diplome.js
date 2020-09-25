$(function(){
  "use-strict";
  
  $("#add-diplome-link").on('click', function() {
    const valueId = $(this).data('etb-id');
    $('body').find('#etablissment-using-id').val(valueId);
    console.log(valueId);
  })

});