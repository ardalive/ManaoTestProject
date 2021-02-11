function submitForm(e){
    let $form = $(e).closest('form');
    let $login = $form.find('.login-error');
    let $password = $form.find('.password-error');
    let $email = $form.find('.email-error');
    let $name = $form.find('.name-error');
    let $success = $form.find('.success');

    $login.empty();
    $password.empty();
    $email.empty();
    $name.empty();
    $success.empty();

    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: $form.serialize()
    }).done(function(msg) {
        let response = JSON.parse(msg);
        if(response.success !== true){
            $login.append(response.login);
            $password.append(response.password);
            $email.append(response.email);
            $name.append(response.name);
        }
        else window.location = "http://localhost";
    });
}