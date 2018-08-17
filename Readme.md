# Backup
Database backup manager

## Concept
This program integrates to your app via Middleware and backups your database according to the configuration. It checks for the last backup, and if the frecuency is met, it runs.

### What it does
* Check last backup
* Extract data from Database
* Save to a File or other Database

### What has been implemented
* MySQL Database extractor
* YAML File saver
* JSON File saver
* XML File saver

### What is in development
* SQL File saver
* MySQL Database saver

## Installation
Get the latest version with `composer aldarien/backup`

## Usage
1. First define a configuration
1. Then load `\App\Middleware\Backup` with the `$app`.

### Configuration

```php
[
  "source" => [
    "driver" => "mysql",
    "database" => [
      "host" => [
        "name" => "<hostname>",
        "[port]" => "<port>"
      ],
      "name" => "<database name>",
      "user" => [
        "name" => "<user name>",
        "password" => "<user password>"
      ]
    ]
  ],
  "output" => [
    [
      "type" => "<file type>",
      "name" => "<filename>"
    ],
    [
      "type" => "<file type>",
      "name" => "<filename>"
    ]
  ],
  "backup" => [
    "location" => "source|file",
    "[file]" => "<filename>"
    "frecuency" => [
      "[hours]" => h,
      "[days]" => d,
      "[months]" => m
    ]
  ]
];
```

+ `backup` The app settings.
  + `location` Where the last backup is registered.
  + `file` If the location is a file, the file setting holds where the file is located. It needs to be writable by the app.
  + `frecuency` How often is the backup made. It can be in months, days or hours.

+ `source` The source database information.
  + `driver` The type of database. Currently only `mysql` is supported.
  + `database` The database information.
    + `host`
      + `name` The host name.
      + `port` optional port different than the default setting.
    + `name` The database name.
    + `user` The user information.
      + `name` The user's name.
      + `password` The user's password.

+ `output` Multiple outputs can be specified. Foreach one the same settings are needed.
  + `type` The file type. Can be `yaml`, `json`, `xml` and `sql`.
  + `name` The filename without the extension.
