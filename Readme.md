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
    "[files]" => [
      "[path]" => "<files path">
      "name" => "<filename>",
      "types" => [
        "<file type1>",
        "<file type2>"
      ]
    ],
    [
      "type" => "<file type>",
      "name" => "<filename>",
      "[path]" => "<file path>"
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
      "value" => <n>,
      "unit" => "hours|days|months"
    ]
  ]
];
```

+ `backup` The app settings.
  + `location` Where the last backup is registered.
  + `file` **Optional**. If the location is a file, the file setting holds where the file is located. It needs to be writable by the app.
  + `frecuency` How often is the backup made.
    + `value` The value of how often to do a backup.
    + `unit` The unit of frecuency. Can be *`hours`*, *`days`* or *`months`*.


+ `source` The source database information.
  + `driver` The type of database. Currently only *`mysql`* is supported.
  + `database` The database information.
    + `host`
      + `name` The host name.
      + `port` **Optional**. The port when it is different than the default settings.
    + `name` The database name.
    + `user` The user information.
      + `name` The user's name.
      + `password` The user's password.


+ `output` Multiple outputs can be specified. Foreach one the same settings are needed.
  + `files` **Optional**. If this is set, then all other output items are ignored. It serves as a summary with all the files named the same and saved in the same place.
    + `path` **Optional**. Where the files are saved.
    + `name` Filename for the different types.
    + `types` Array with all the file types.
  + `type` The file type. Can be *`yaml`*, *`json`*, *`xml`* and *`sql`*.
  + `name` The filename without the extension.
  + `path` **Optional**. The location of the saved file.
