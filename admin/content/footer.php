<?php

if (isset($_SESSION['user_data']['user_login'])) {

echo '
</div>
</div>

<div class="footer">
    <br>
    <p>Hotspots for Coffeecuptogo, 2020</p>
</div>

</body>
</html>
';


}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}
?>