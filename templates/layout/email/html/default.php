<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title><?= $this->fetch('title') ?></title>
    <style>
        body {
            font-family: "MuseoSans", Sans-Serif;
        }

        .container {
            margin: 20px auto;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            max-width: 600px;
            overflow: hidden;
        }

        h1 {
            margin: 0px 0px 10px;
            padding: 5px 45px;
            background-color: #46b6ae;
        }

        .content {
            padding: 10px 45px 30px;
        }

        h3 {
            margin-top: 0px;
        }
    </style>
</head>

<body style="font-family: 'MuseoSans', Sans-Serif;">
    <?= $this->fetch('content') ?>
</body>

</html>