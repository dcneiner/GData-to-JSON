## Google Spreadsheet to PHP Array/JSON Converter

This is something I patched together because I needed a simple exporter from Google Docs Spreadsheet to JSON/PHP Array. I will build on it over time, **but it is not actively maintained**. It is released here if it helps someone doing the same thing.

### Setup

Copy `events-importer/config.sample.php` to `events-importer/config.php` and edit it to provide your credentials for Google Docs. _(Note, do not use this method to access other people's accounts. Google provides an authentication process for those users so you don't need to ask for their credentials.)_

### Use

There is basically two primary steps before you can get data out of a Worksheet:

1. At the command line, run `import.php` with the `list` command. It should list out all accessible spreadsheets in your account and give you the special key for working with it.

        php -f import.php list
        
2. Next, list out the worksheets in the selected spreadsheet. You use the key obtained from step 1 (Where XXXXXXX is in the following example).

        php -f import.php list-worksheets XXXXXXXXXXXXXXXXXXXX
        
3. Finally, you can either get JSON with these details, or a PHP array (ob1 should be replaced with the correct worksheet id).

        php -f import.php export XXXXXXXXXXXXXXXXXXXX ob1 > spreadsheet.json
        
Or via PHP (Which returns a PHP associative array)

    <?php
        $spreadsheet = "XXXXXXXXXXXXXXXXXXXXXXXXXX";
        $worksheet   = "ob1";
        $data        = include 'import.php';
        

## License

Copyright 2010 by Douglas C. Neiner

Dual licensed under the MIT or GPL license. Included is the Zend GData library which has its own license.