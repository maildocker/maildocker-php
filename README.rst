Maildocker-PHP
==============

This library allows you to quickly and easily send emails through
Maildocker using PHP.

Example
-------

.. code:: php

    require_once "maildocker/Maildocker.php";

    $md = new MaildockerClient('0cc175b9c0f1b6a831c', '92eb5ffee6ae2fec3ad');

    $message = new Maildocker\Mail();

    $message
        ->set_from('maildocker@ecentry.io', 'Maildocker')
        ->add_to('john.snow@thrones.com', 'John Snow')
        ->set_subject('maildocker-php-library')
        ->set_text('**{{system}}** ({{url}})')
        ->add_vars(array('system' => 'Maildocker', 'url' => 'http://maildocker.io'))
        ->add_attachment(array(
            array('name' => 'plaintext.txt', 'type' => 'text/plain', 'content' => 'dHN0'),
            'spreadsheet.xls'
        ));

    list($http_status, $response) = $md->send($message);
