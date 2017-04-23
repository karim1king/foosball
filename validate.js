function validateRegEx(regex, input, helpText, helpMessage) {
    if (!regex.test(input)) {
        if (helpText != null)
            helpText.innerHTML = helpMessage;
        return false;
    }
    else {
        if (helpText != null)
            helpText.innerHTML = "";
        return true;
    }
}

function validateNonEmpty(inputField, helpText)
{
    return validateRegEx(/.+/,
        inputField.value, helpText,
        "Пожалуйста введите значение.");
}

function validateLength(minLength, maxLength, inputField, helpText)
{
    return validateRegEx(new RegExp("^.{" + minLength + "," + maxLength + "}$"),
        inputField.value, helpText,
        "Пожалуйста введите от" + minLength + " до " + maxLength +
        "символов .");
}

function validateDate(inputField, helpText)
{
    if (!validateNonEmpty(inputField, helpText))
        return false;
    return validateRegEx(/^\d{2}\/\d{2}\/(\d{2}|\d{4})$/,
        inputField.value, helpText,
        "Пожалуйста введите дату (например , 14/01/1975).");
}


function validateEmail(inputField, helpText)
{
    if (!validateNonEmpty(inputField, helpText))
        return false;

    return validateRegEx(/^[\w\.-_\+]+@[\w-]+(\.\w{2,3})+$/,
        inputField.value, helpText,
        "Пожалуйста, введите адрес электронной почты (например, karim@yahoo.com).");
}

function twoPassword(form)
{
    if(form['password1'].value != form['password2'].value)
    {
        var elem = document.getElementById('password_help2');
        elem.innerHTML = "Введенные пароли не совпадают";
        return false;
    }
    return true;
}
function placeOrder(form)
{
    if (
        validateNonEmpty(form['login'], document.getElementById('login_help'))&&
        validateNonEmpty(form['name'], document.getElementById('name_help'))&&
    validateNonEmpty(form['surname'], document.getElementById('surname_help'))&&
    validateEmail(form['email'], document.getElementById('email_help'))&&
    validateLength(6,16,form['password1'], document.getElementById('password_help1'))&&
    validateLength(6,16,form['password2'], document.getElementById('password_help2'))&&
        twoPassword(form)
    )
    {
        form.submit();
    }
}
