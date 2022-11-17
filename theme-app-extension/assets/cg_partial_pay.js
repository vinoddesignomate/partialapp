async function loadSomething() {
    while (!window.hasOwnProperty("jQuery")) {
        await new Promise(resolve => setTimeout(resolve, 100));
    }

    jQuery(function () {
        jQuery('body').on('click', '[name="checkout"], [name="goto_pp"], [name="goto_gc"]', function () {
            var formIsValid = true;
            var message = "Please inform us ........";
            jQuery('[name^="attributes"], [name="note"]').filter('.required, [required="required"]').each(function () {
                jQuery(this).removeClass('error');
                if (formIsValid && jQuery(this).val() == '') {
                    formIsValid = false;
                    message = jQuery(this).attr('data-error') || message;
                    jQuery(this).addClass('error');
                }
            });
            if (formIsValid) {
                jQuery(this).submit();
            }
            else {
                alert(message);
                return false;
            }
        });
    });

    $(window).on("beforeunload", function () {
        var cartForm = jQuery('form[action="/cart"]');
        if (cartForm.size()) {
            var params = {
                type: 'POST',
                url: '/cart/update.js',
                data: cartForm.serialize(),
                dataType: 'json',
                async: false
            };
            jQuery.ajax(params);
        }
    });

}

loadSomething();