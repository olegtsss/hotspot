<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
<form method="post">
	<div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Указать дату</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" placeholder="Вывести на экран все даты" name="filter_parameter_date_input">
        </div>
    </div>

   	
   	<div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Указать телефон</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" placeholder="Вывести на экран все телефоны" name="filter_parameter_user_input">
        </div>
    </div>



	<div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Указать бар</label>
        <div class="col-sm-10">
            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="filter_parameter_coffeepoint_input">
            <option selected>Вывести на экран все бары</option>
';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>
