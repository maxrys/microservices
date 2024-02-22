
Simple microservice based on pure PHP.

Run `./docker/build.sh` to build a project under Docker.



# Schema:

`transactions`:

    CREATE TABLE IF NOT EXISTS `transactions` (
        `id`      int(11) unsigned NOT NULL,
        `paid_to` int(11) unsigned NOT NULL,
        `paid_by` int(11) unsigned NOT NULL,
        `amount`  decimal(5,2) unsigned NOT NULL,
        `trdate`  datetime NOT NULL,
        PRIMARY KEY (`id`),    
        KEY `paid_to` (`paid_to`),
        KEY `paid_by` (`paid_by`)
    ) DEFAULT CHARSET=utf8;

    INSERT INTO `transactions` (`id`,`paid_by`,`paid_to`,`amount`,`trdate`)
    VALUES
       (01, 01, 10, 6.00, '2021-01-01'),
       (02, 01, 11, 3.00, '2021-01-02'),
       (03, 01, 12, 4.00, '2021-01-03'),
       (04, 10, 02, 2.00, '2021-01-04'),
       (05, 10, 02, 1.00, '2021-01-04'),
       (06, 11, 02, 6.00, '2021-01-06'),
       (07, 11, 02, 7.00, '2021-01-06'),
       (08, 12, 02, 2.00, '2021-01-07'),
       (09, 12, 02, 3.00, '2021-01-08'),
       (10, 10, 02, 1.00, '2021-01-09'),
       (11, 01, 10, 3.00, '2021-01-10'),
       (12, 10, 02, 5.00, '2021-01-10');

`short_links`:

    CREATE TABLE `short_links` (
    `id` binary(6) NOT NULL,
    `link` varchar(2047) NOT NULL,
    PRIMARY KEY (`id`)
    );
