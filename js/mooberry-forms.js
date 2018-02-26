jQuery(document).ready(function () {

    jQuery('.moobd-forms-required').prevAll('label').addClass('moobd-forms-required');

    // intl-tel-input
    var telInput = jQuery('input.moobd-forms-phone'),
    errorMsg = jQuery(".moobd-forms-phone-error-msg"),
    validMsg = jQuery(".moobd-forms-phone-valid-msg");
    telInput.intlTelInput({
          initialCountry: "auto",
              geoIpLookup: function(callback) {
                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                  var countryCode = (resp && resp.country) ? resp.country : "";
                  callback(countryCode);
                });
              },
            utilsScript: "../intl-tel-input/js/utils.js"
        }
    );

    var reset = function() {
        telInput.removeClass("error");
        errorMsg.hide();
        validMsg.hide();
    };

    var validate_phone_number = function () {
        reset();

        if (jQuery.trim(jQuery(this).val())) {
            if (jQuery(this).intlTelInput("isValidNumber")) {
                jQuery(this).parent('div').nextAll('.moobd-forms-phone-valid-msg').show();
            } else {
                jQuery(this).addClass("error");
                jQuery(this).parent('div').nextAll('.moobd-forms-phone-error-msg').show();
            }
        }

    }

    // on blur: validate
    telInput.on("blur", validate_phone_number );

    // on keyup / change flag: reset
    telInput.on("keyup change", validate_phone_number);

    // set the number to the international format before saving
    // and prevent saving if invalid
    jQuery('.moobd_forms-btn-save').on( 'click', function( event ) {
            jQuery('input.moobd-forms-phone').each( function() {
                jQuery(this).val(jQuery(this).intlTelInput("getNumber"));
                if ( jQuery(this).val() != '' && !jQuery(this).intlTelInput("isValidNumber") ) {
                     jQuery(this).addClass("error");
                     jQuery(this).parent('div').nextAll('.moobd-forms-phone-error-msg').show();
                    event.preventDefault();
                }

            });
    });

    // reset the form
    reset();


});

