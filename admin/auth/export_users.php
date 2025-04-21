<?php

if (isset($_SESSION['user_data']['user_login'])) {


//Если есть что экспортировать
if ($result_users != null) {
    //Экспорт в Exel кнопка
    export_exel_button ('users');
    //Фильтруем форму post для кнопки export
    $filter_export_button = trim(htmlspecialchars($_POST['export']));
        //Нажата кнопка экспорт
        if ($filter_export_button === 'yes') {
            //Папка, куда будут сохраняться папки с экспортом
            $nameaddfull = 'export_files/';
            //Имя файла для экспорта
            $export_file_name_start = 'users.xls';
            $export_file_name = $export_file_name_start;
                //Генерируем случайное имя
                for($i = 0; $i < 29; $i++) {
                    $nameadd = random_int (0, 9);
                    $nameaddfull = $nameaddfull.$nameadd;
                }
            //Создаем директорию со случайным именем
            // ВАЖНО !!!!   chown www-data:www-data /var/www/html/export_files
            mkdir($nameaddfull, 0700);
            $export_file_name = $nameaddfull.'/'.$export_file_name;
            //Имя сервера
            $export_server_name = 'https://'.$_SERVER['SERVER_NAME'].'/';
            //Вывод всех пользователей без учета страниц перемотки
            $query_users_export = "SELECT * FROM users 
    			INNER JOIN coffeepoints on users.users_first_reg = coffeepoints.coffeepoints_id
    			$filter_query_2
    			ORDER BY users.users_telefon ASC";
            $result_users_export = mysqli_fetch_all(mysqli_query($connect, $query_users_export), MYSQLI_ASSOC);

            // Создаем объект класса PHPExcel
            $xls = new PHPExcel();
            // Устанавливаем индекс активного листа
            $xls->setActiveSheetIndex(0);
            // Получаем активный лист
            $sheet = $xls->getActiveSheet();
            // Подписываем лист
            $sheet->setTitle('Пользователи');

            // Вставляем текст в ячейку A1
            $sheet->setCellValue("A1", 'Пользователь');
            $sheet->setCellValue("B1", 'Откуда пришел');
            $sheet->setCellValue("C1", 'Комментарий');
            $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('EEEEEE');
            //Устанавливает столбцу “A” ширину в 40 единиц
            //$sheet->getColumnDimension('A')->setWidth(40)
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            //Задать тип шрифта
            $sheet->getStyle('A')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('B')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('C')->getFont()->setName('TimesNewRoman');
            //Жирный шрифт
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('B1')->getFont()->setBold(true);
            $sheet->getStyle('C1')->getFont()->setBold(true);
            // Выравнивание текста
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                foreach ($result_users_export as $key => $value) { 
                    $sheet->setCellValueByColumnAndRow(0, ($key+2), $value['users_telefon']);
                    $sheet->setCellValueByColumnAndRow(1, ($key+2), $value['coffeepoints_city']." ".$value['coffeepoints_point_name']." ".$value['coffeepoints_comment']);
                    $sheet->setCellValueByColumnAndRow(2, ($key+2), $value['users_comment']);

                }

            // Выводим содержимое файла
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save($export_file_name);
            // Записываем информацию в базу данных экспорта
            $export_time = time(); // Получаем время
            $export_id = uniqid(); // Генерим id для базы экспорта
            $export_add_to_database = "INSERT INTO export_files (id, time_create, file_path, file_name) VALUES ('$export_id', '$export_time', '$nameaddfull', '$export_file_name_start')";
            mysqli_query($connect_brute, $export_add_to_database);

            //Напечатать окно успеха
            success_export_window('<a href="'.$export_server_name.$export_file_name.'">ссылку</a>');

        }
echo '<br>';
}



}
// Ошибка при обращении к внутренностям сайта
else {
    echo 'Error';
}

?>