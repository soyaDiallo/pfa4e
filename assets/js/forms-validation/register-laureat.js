$(function(){
  
  

  //Validation Steps
  var formValidate = $("#steps-laureat-register-form");
  var stepsId = $("#steps-id");
  
  stepsId.steps({

    onInit: function(event, currentIndex) {
      // $("#steps-id select").select2({
      //   placeholder: "select date"
      // });
      // $("#steps-id select").val(2).trigger('change.select2');
    },
    headerTag: 'h3',
    bodyTag: 'section',
    autoFocus: true,
    titleTemplate: '<span class="fx-step-number">#index#</span> <span class="fx-step-title">#title#</span>',
    onStepChanging: function (event, currentIndex, newIndex) {
      if(currentIndex < newIndex) {
        // Step 1 form validation
        if(currentIndex === 0) {
          formValidate.validate({
            debug: false,
            errorElement: "span",
            errorClass: "is-invalid text-danger",
            rules: {
              "laureat_registration_form[pseudo]": {required: true},
              "laureat_registration_form[nom]": {required: true},
              "laureat_registration_form[prenom]": {required: true},
              "laureat_registration_form[email]": {required: true,email: true},
              "laureat_registration_form[datenaissance]": {required: true},
              "laureat_registration_form[plainPassword]": {required: true, minlength: 8, maxlength: 24}
            },
            messages: {
              "laureat_registration_form[pseudo]": "Please enter Username",
              "laureat_registration_form[nom]": "Pleas Enter Valid Name",
              "laureat_registration_form[prenom]": "Please enter Valid Prenom",
              "laureat_registration_form[email]": "Please enter Valid email Address",
              "laureat_registration_form[datenaissance]": "Please enter date Naissance",
              "laureat_registration_form[plainPassword]": {
                required: "Please enter Valid Password",
                minlength: "Your password must consist of at least 8 characters",
                maxlength: "Your password must be maximum 24 characters"
              },
            }
          });
          return formValidate.valid();
        }
      // Always allow step back to the previous step even if the current step is not valid.
      } else { 
        return true; 
      }
    },
    onFinishing: function (event, currentIndex) {
      formValidate.validate({
        debug: false,
        errorElement: "span",
        errorClass: "is-invalid text-danger",
        rules: {
          "laureat_registration_form[addresse]": {required: true},
          "laureat_registration_form[telephone]": {required: true, number: true, minlength: 10,maxlength: 10},
          "laureat_registration_form[cinNumSejour]": {required: true},
          "laureat_registration_form[lieuNaissance]": {required: true,},
          "laureat_registration_form[nationalite]": {required: true}
        }
      });
      return formValidate.valid();
    },
    onFinished: function (event, currentIndex) {
      formValidate.submit();
    }
  });

  // $("#steps-id > .steps > ul > li").each( function(e) {
  //   console.log(e);
  //   if(!$(this).hasClass("current")) {
  //     $(this).find('a>span').last().hide();
  //   } else {
  //     $(this).find('a>span').last().show();
  //   }
  // });
}); 
