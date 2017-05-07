var formId, allEnable;

formId = '#form-company-create';
allEnable = false;

function adjustIframeHeight() {
    var $body   = $('body'),
        $iframe = $body.data('iframe.fv');
    if ($iframe) {
        // Adjust the height of iframe
        $iframe.height($body.height());
    }
}

$(formId)
    .steps({
        headerTag: 'h2',
        bodyTag: 'section',
        enableAllSteps: allEnable,
        onStepChanged: function(e, currentIndex, priorIndex) {
            adjustIframeHeight();
        },

        onStepChanging: function(e, currentIndex, newIndex) {
            var fv         = $(formId).data('formValidation'), // FormValidation instance
                // The current step container
                $container = $(formId).find('section[data-step="' + currentIndex +'"]');

            // Validate the container
            fv.validateContainer($container);

            var isValidStep = fv.isValidContainer($container);
            if (isValidStep === false || isValidStep === null) {
                return false;
            }

            return true;
        },

        onFinishing: function(e, currentIndex) {
            var fv         = $(formId).data('formValidation'),
                $container = $(formId).find('section[data-step="' + currentIndex +'"]');


            fv.validateContainer($container);

            var isValidStep = fv.isValidContainer($container);
            if (isValidStep === false || isValidStep === null) {
                return false;
            }

            return true;
        },
        onFinished: function(e, currentIndex) {
            $(formId).formValidation('defaultSubmit');
        }
    })
    .formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        // This option will not ignore invisible fields which belong to inactive panels
        excluded: ':disabled',
        fields: {
            'company[email]': {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            'company_registration[site]': {
                validators: {
                    empty: true,
                    regexp: {
                        regexp: /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/,
                        message: 'The site name contains incorrect structure'
                    }
                }
            }
        }
    });