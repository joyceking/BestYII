<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;
use \yii\widgets\ListView;
use yii\grid\GridView;
use common\models\MCourse;
use common\models\MPhoto;
use common\models\MStudent;
use common\models\MCourseSchedule;
use common\models\MGroup;
use common\models\MClass;
use common\models\MTeacher;
use common\models\MRoom;
?>

<!DOCTYPE html>
<html lang="zh-CN">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <!-- 新 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <!-- 可选的Bootstrap主题文件（一般不用引入） -->
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
        <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
        <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
        <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
        <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

        <title>宝贝课表</title>

    </head>

    <style type="text/css">
        body{padding: 10px;}
        .info td,.success td,.table>tbody>tr>td{
            text-align:center;
            vertical-align: middle;
        }
    </style>

    <body>

        <img src='<?php echo MPhoto::getUploadPicUrl('mycourse.jpg') ?>' width='100%' class="img-rounded">
        <br><br>

        <table class="table table-bordered">
         <!-- <caption>宝贝课表一</caption> -->

            <?php foreach ($students as $student) { ?>
                <tr class="info">
                    <td width="30%">学生</td>
                    <td><?php echo $student->name ?></td>
                </tr>
                <?php
                $studentSchedules = $student->courseSchedules;
                $groupTitle = $studentSchedules[0]->group->title;
                $courseTitle = $studentSchedules[0]->group->course->title;
                ?>
                <tr class="success">
                    <td>班级</td>
                    <td style="text-align:center;vertical-align: middle;"><?php echo $courseTitle ?>/<?php echo $groupTitle ?></td>
                </tr>
                <?php
                foreach ($studentSchedules as $studentSchedule) {
                    ?>
                    <tr>
                        <td rowspan='3'><?php echo $studentSchedule->courseUnit->title ?></td>
                    </tr>
                    <tr>
                        <td><?php echo empty($studentSchedule->teacher->name) ? '--' : $studentSchedule->teacher->name ?></td>
                    </tr>
                    <tr>
                        <td>上课时间<?php echo $studentSchedule->start_time ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>

    </body>

</html>