<?php

if (isset($_SESSION['user_data']['user_login'])) {
	
echo '
            </select>
        </div>
    </div>

 
    <button type="submit" class="btn btn-primary" id="button_update_page_2" name="link" value="registrations">Обновить</button>
    <input type="hidden" name="page" value="'.--$_SESSION['user_data']['page_memory'].'">
    <input type="hidden" name="submit" value="yes">
</form>

<br>';

}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>

    
