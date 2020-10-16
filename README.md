# php-db
Simple PHP package to manage database

## Usage

### Using Database

You can use database directly, from static method `exec`:

```php
    Database::exec( 'DROP DATABASE IF EXISTS activerecord;');
    Database::exec( 'CREATE DATABASE activerecord DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;');
    Database::exec( 'USE activerecord;');
    Database::exec( 'DROP TABLE IF EXISTS cliente;');
    $sql = <<<EOV
    CREATE TABLE client 
    (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        name        VARCHAR(80) NOT NULL,
        address     TEXT,
        updated_at  DATETIME,
        created_at  DATETIME
    );
EOV;
    Database::exec( $sql );
```

### Creating a model

```php
use LCloss\DB\ActiveRecord;

class Client extends ActiveRecord
{
    // Define automatically filled columns updated_at and created_at
    protected $log_timestamp = true;

    public function lastClients( $days = 7 )
    {
        return Client::all("created_at > '" . date('Y-m-d h:m:i', strtotime("-{$days} days") ));
    }
}
```
### Insert rows

```php
    $client = new Client();
    $client->name = "Some client";
    $client->address = "Some address street";

    $client->save();
```

### Select rows by `id`

```php
    $client = Client::find(1);
```

### Select all data

```php
    $clients = Client::all();
```

### Navigating

```php
    $cond = "name LIKE 'Some%'";
    $limit = 10;
    $offset = 20;
    $clients = Client::all($cond, $limit, $offset);
```

### Update rows

```php
    $client = Client::find(1);

    $client->name = "Changed to this name";
    $client->save();
```

### Delete rows

```php
    $client = Client::find(1);
    $client->delete();
```

### Retrieving a list of rows

```php
    for ($i = 1; $i < 11; $i++) {
        $client = new Client();
        $client->name = "Client {$i}";
        $client->address = "Street number {$i}";
        
        if ( $client->save(); ) {
            echo "Client {$i} saved!\n<br />";
        } else {
            echo "There are a problem when saving client {$i}!\n<br />";
        }
    }

    $clients = Client::all();

    foreach( $clients as $client )
    {
        echo $client->name . "\n<br />";
    }
```

### Find first row

```php
    $client = Client::findFirst("name = 'Client 4'");
    echo $client->name . "\n<br />";
```

### Retrieving a list with conditions

```php
    $res = Client::all("address = 'Street 1'");
    foreach( $clients as $client )
    {
        echo $client->name . "\n<br />";
    }
```

### Counting rows

```php
    $rows = Client::count();
    echo "There are {$rows} clients on database.\n<br />";
```