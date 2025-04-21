<?php

if (isset($_SESSION['user_data']['user_login'])) {


//Если есть что экспортировать
if ($result_registrations != null) {
    //Экспорт в Exel кнопка
    export_exel_button ('registrations');
    //Фильтруем форму post для кнопки export
    $filter_export_button = trim(htmlspecialchars($_POST['export']));
        //Нажата кнопка экспорт
        if ($filter_export_button === 'yes') {
            //Папка, куда будут сохраняться папки с экспортом
            $nameaddfull = 'export_files/';
            //Имя файла для экспорта
            $export_file_name_start = 'registartions.xls';
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
            //Вывод журнала регистраций без учета страниц перемотки
            $query_registrations_export = "SELECT * FROM registration 
                INNER JOIN coffeepoints on registration.registrations_id_coffeepoints = coffeepoints.coffeepoints_id
                INNER JOIN status on registration.registrations_status_code = status.status_id
                $filter_query
                ORDER BY registration.registrations_id DESC";
            $result_registrations_export = mysqli_fetch_all(mysqli_query($connect, $query_registrations_export), MYSQLI_ASSOC);

            //var_dump($result_registrations);      
            // Создаем объект класса PHPExcel
            $xls = new PHPExcel();
            // Устанавливаем индекс активного листа
            $xls->setActiveSheetIndex(0);
            // Получаем активный лист
            $sheet = $xls->getActiveSheet();
            // Подписываем лист
            $sheet->setTitle('Регистрации');

            // Вставляем текст в ячейку A1
            $sheet->setCellValue("A1", '№ п\п');
            $sheet->setCellValue("B1", 'Дата');
            $sheet->setCellValue("C1", 'Время');
            $sheet->setCellValue("D1", 'Бар');
            $sheet->setCellValue("E1", 'Пользователь');
            $sheet->setCellValue("F1", 'MAC');
            $sheet->setCellValue("G1", 'Хост');
            $sheet->setCellValue("H1", 'IP nat');
            $sheet->setCellValue("I1", 'IP серый');
            $sheet->setCellValue("J1", 'IP белый');
            $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('D1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('E1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('F1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('G1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('H1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('I1')->getFill()->getStartColor()->setRGB('EEEEEE');
            $sheet->getStyle('J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('J1')->getFill()->getStartColor()->setRGB('EEEEEE');
            //Устанавливает столбцу “A” ширину в 40 единиц
            //$sheet->getColumnDimension('A')->setWidth(40)
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            //Задать тип шрифта
            $sheet->getStyle('A')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('B')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('C')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('D')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('E')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('F')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('G')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('H')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('I')->getFont()->setName('TimesNewRoman');
            $sheet->getStyle('J')->getFont()->setName('TimesNewRoman');
            //Жирный шрифт
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('B1')->getFont()->setBold(true);
            $sheet->getStyle('C1')->getFont()->setBold(true);
            $sheet->getStyle('D1')->getFont()->setBold(true);
            $sheet->getStyle('E1')->getFont()->setBold(true);
            $sheet->getStyle('F1')->getFont()->setBold(true);
            $sheet->getStyle('G1')->getFont()->setBold(true);
            $sheet->getStyle('H1')->getFont()->setBold(true);
            $sheet->getStyle('I1')->getFont()->setBold(true);
            $sheet->getStyle('J1')->getFont()->setBold(true);
            // Выравнивание текста
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                foreach ($result_registrations_export as $key => $value) { 
                    $sheet->setCellValueByColumnAndRow(0, ($key+2), $value['registrations_id']);
                    $sheet->setCellValueByColumnAndRow(1, ($key+2), $value['registrations_date']);
                    $sheet->setCellValueByColumnAndRow(2, ($key+2), substr($value['registrations_time'], 0, 2).":"
            .substr($value['registrations_time'], 2, 2).":".substr($value['registrations_time'], 4, 2));
                    $sheet->setCellValueByColumnAndRow(3, ($key+2), $value['coffeepoints_city']." ".$value['coffeepoints_point_name']." ".$value['coffeepoints_comment']);
                    $sheet->setCellValueByColumnAndRow(4, ($key+2), $value['registrations_users']);
                    $sheet->setCellValueByColumnAndRow(5, ($key+2), $value['registrations_mac']);
                    $sheet->setCellValueByColumnAndRow(6, ($key+2), $value['registrations_host_name']);
                    $sheet->setCellValueByColumnAndRow(7, ($key+2), $value['registrations_ip_nat']);
                    $sheet->setCellValueByColumnAndRow(8, ($key+2), $value['registrations_ip_gray']);
                    $sheet->setCellValueByColumnAndRow(9, ($key+2), $value['registrations_ip_white']);

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