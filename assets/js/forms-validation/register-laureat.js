//Validation Steps
var formValidate = $("#steps-laureat-register-form");
var stepsId = $("#steps-id");

$(document).ready(function() {


stepsId.steps({
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
            "laureat_registration_form[prenomArabe]": {required: true},
            "laureat_registration_form[nomArabe]": {required: true},
            "laureat_registration_form[email]": {required: true,email: true},
            "laureat_registration_form[plainPassword]": {required: true, minlength: 8,maxlength: 8}
          },
          messages: {
            "laureat_registration_form[pseudo]": "Please enter Username",
            "laureat_registration_form[nom]": "Pleas Enter Valid Name",
            "laureat_registration_form[prenom]": "Please enter Valid Prenom",
            "laureat_registration_form[prenomArabe]": "Please enter Valid Arabic Prenom",
            "laureat_registration_form[nomArabe]": "Please enter Valid Arabic Name",
            "laureat_registration_form[email]": "Please enter Valid email Address",
            "laureat_registration_form[plainPassword]": "Please enter Valid Password",
          }
        });
        return formValidate.valid();
      }

      // Step 2 form validation
      if(currentIndex === 1) {
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
        // "laureat_registration_form[datenaissance][month]": {required: true},
        // "laureat_registration_form[datenaissance][day]": {required: true},
        // "laureat_registration_form[datenaissance][year]": {required: true},
        "laureat_registration_form[photoUrl]": {required: true}
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
