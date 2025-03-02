import jQueryValidation from 'jquery-validation/dist/jquery.validate';

try {
    window.jQueryValidation = jQueryValidation;
} catch (e) {}

export { jQueryValidation };
