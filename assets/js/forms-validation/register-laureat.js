$(function(){
  
  //Validation Steps
  let $body = $("body"),
      formValidate = $("#steps-laureat-register-form"),
      formEntrpriseValidate = $("#steps-entreprise-register-form"),
      formEtablValidate = $("#steps-etablissment-register-form"),
      stepsId = $("#steps-id");
  
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
              "etablissement_registration_form[nomEtablissement]": {required: true},
              "etablissement_registration_form[telephone]": {required: true},
              "etablissement_registration_form[pays]": {required: true},
              "etablissement_registration_form[email]": {required: true,email: true},
              "laureat_registration_form[datenaissance]": {required: true},
              "laureat_registration_form[plainPassword]": {required: true, minlength: 8, maxlength: 24}
            },
            messages: {
              "etablissement_registration_form[nomEtablissement]": "Please enter Username",
              "etablissement_registration_form[telephone]": "Pleas Enter Valid Name",
              "etablissement_registration_form[pays]": "Please enter Valid Prenom",
              "etablissement_registration_form[email]": "Please enter Valid email Address",
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


  if($body.find("#etablissement_registration_form_pays").length) {
		console.log("yyyy")
		$("#etablissement_registration_form_pays").select2({
			placeholder: "select pay"
		});
  }
  
  formEtablValidate.validate({
    // validate signup form on keyup and submit
    debug: false,
      errorElement: "span",
      errorClass: "is-invalid text-danger",
      rules: {
        "etablissement_registration_form[nomEtablissement]": {required: true},
        "etablissement_registration_form[telephone]": {required: true, minlength: 10, maxlength: 10},
        "etablissement_registration_form[pays]": {required: true},
        "etablissement_registration_form[email]": {required: true,email: true},
        "etablissement_registration_form[plainPassword]": {required: true, minlength: 8, maxlength: 24}
      },
      messages: {
        "etablissement_registration_form[nomEtablissement]": "Please enter etablissment Name",
        "etablissement_registration_form[telephone]": {
          required: "Please enter Valid Password",
          minlength: "please provide valid phone number",
          maxlength: "please provide valid phone number"
        },
        "etablissement_registration_form[pays]": "Please select Country",
        "etablissement_registration_form[email]": "Please enter Valid email Address",
        "etablissement_registration_form[plainPassword]": {
          required: "Please enter Valid Password",
          minlength: "Your password must consist of at least 8 characters",
          maxlength: "Your password must be maximum 24 characters"
        },
      }
  });
  formEntrpriseValidate.validate({
    // validate signup form on keyup and submit
    debug: false,
      errorElement: "span",
      errorClass: "is-invalid text-danger",
      rules: {
        "entreprise_registration_form[nomEntreprise]": {required: true},
        "entreprise_registration_form[telephone]": {required: true, minlength: 10, maxlength: 10},
        "entreprise_registration_form[addresse]": {required: true},
        "entreprise_registration_form[email]": {required: true,email: true},
        "entreprise_registration_form[plainPassword]": {required: true, minlength: 8, maxlength: 24}
      },
      messages: {
        "entreprise_registration_form[nomentreprise]": "Please enter etablissment Name",
        "entreprise_registration_form[telephone]": {
          required: "Please enter Valid Password",
          minlength: "please provide valid phone number",
          maxlength: "please provide valid phone number"
        },
        "entreprise_registration_form[addresse]": "Please enter ur Addresse",
        "entreprise_registration_form[email]": "Please enter Valid email Address",
        "entreprise_registration_form[plainPassword]": {
          required: "Please enter Valid Password",
          minlength: "Your password must consist of at least 8 characters",
          maxlength: "Your password must be maximum 24 characters"
        },
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
