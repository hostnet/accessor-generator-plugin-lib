Welcome to the Accessor Generator composer plugin.

## Goals
The goal of this plugin is to provide dynamically generated get, set, add, remove
accessor methods for Classes based on information that we can read from the doc comment.
Currently we can process Doctrine ORM annotations.

Since the code is automatically generated you do not have to (unit) test it and it
will be very consistent with a lot of added boilerplate code that will make your code
fail early if you happen to use the wrong type or number of arguments with the generated
functions.

> **WARNING** The current version of this package is deprecated as a new
> version is being worked on for PHP 7, which will introduce BC-breaks.

## Limitations

- Imports through grouped `use` statements is not supported. (https://wiki.php.net/rfc/group_use_declarations)
- Scalar typehints is not being added (https://wiki.php.net/rfc/scalar_type_hints_v5)

## Usage

```php
<?php
namespace Hostnet\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 * @ORM\Table(name="periode")
 */
class Period
{
    use \Hostnet\Product\Entity\Generated\PeriodMethodsTrait; // This is the file that gets generated with the
                                                              // accessor methods inside.
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @AG\Generate                                           // Here you ask methods to be generated
     *
     * @var int
     */
    private $id;

    // ...
}
```

Code will be generated in a subfolder and namespace (Generated) relative to the current
file. This file can be included as trait.

### Which methods

It is possible to disable generation of certain accessor methods by specifying them in
the annotation.

```php
/**
 * @AG\Generate(add=false,set=false,remove=false,get=false,is=false)
 */
```

`Is` is an alias for get. If your property is of type boolean an `isProperty` method is
generated instead of a `getProperty` method. For `ORM\GeneratedValue` properties, no
setters will be generated. Note that the example above will generate no code at all.

If no configuration is specified, the default behaviour for all scalar typed properties is
that a getter and a settter method will be generated. Adders and Removers will be generated
when the type is iterable (e.g. DoctrineCollection or array).

### Encryption

To use asymmetric encryption on a column's value add the 'encryption_alias' field to the `Generate` annotation. 
Also make sure the type of the database column has a big enough length. At least 1064 for key and IV is needed,
plus the length of the sealed data itself.

```php
/**
 * @AG\Generate(encryption_alias="database.table.column")
 */
 private $encrypted_variable;
```

The alias used there should be added to the application's composer.json as follows:

```
"extra": {
     "accessor-generator": {
         <encryption_alias>: {
             "public-key": <public_key_file>
             "private-key": <private_key_file>
...
```

In order to encrypt or decrypt data, a valid private and public key must be specified.

- The *public key* is needed to encrypt data.
- The *private key* is needed to decrypt data.

In order to start encrypting data, a public key is necessary. However, you will first need a private key in order to 
extract a public key from it. We can use the `openssl` tool to do so: 

**Creating a key:**
```bash
$ openssl genrsa -out database.table.column.private_key.pem 2048
```

**Extracting a public key from a private key:**
```bash
$ openssl rsa -in database.table.column.private_key.pem -pubout > database.table.column.public_key.pem
```

If the application has to _encrypt_, add the _public key_. If the application has to _decrypt_, add the _private key_.
If the application has to do both, add both.

The `<public_key_file>` and `<private_key_file>` values have to contain the file paths to the keys relative 
to the composer.json file.

Do not forget to use the *setter method* in the constructor to trigger the encryption of the given value instead
of assigning a value to the property directly.
.

```php
<?php
class MyEntity
{
    /**
     * @AG\Generate(encryption_alias="<encryption_alias>")
     */
    private $my_value;
    
    public function __construct(string $my_value)
    {
        $this->my_value = $my_value;  // No encryption is taking place.
        $this->setMyValue($my_value); // The value is now encrypted in the field.
    }
}
```

## Installation

Add `hostnet/accessor-generator-plugin-lib` to your `composer.json` and run
`php composer.phar update hostnet/accessor-generator-plugin-lib`

If you want to invoke generation after installing you can run `php composer.phar dump-autoload`.
Try adding `-vv` to the dump-autoload command for more verbosity.
