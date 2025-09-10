Welcome to the Accessor Generator composer plugin.

## Goals
The goal of this plugin is to provide dynamically generated get, set, add, remove
accessor methods for Classes based on information that we can read from the doc comment.
Currently we can process Doctrine ORM annotations.

Since the code is automatically generated you do not have to (unit) test it and it
will be very consistent with a lot of added boilerplate code that will make your code
fail early if you happen to use the wrong type or number of arguments with the generated
functions.

## Limitations

- Imports through grouped `use` statements is not supported. (https://wiki.php.net/rfc/group_use_declarations)

## Installation

Add `hostnet/accessor-generator-plugin-lib` to your `composer.json` and run
`composer require hostnet/accessor-generator-plugin-lib`

If you want to invoke generation after installing you can run `php composer.phar dump-autoload`.
Add `-vv` to the dump-autoload command for more verbosity.

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
    use Generated\PeriodMethodsTrait;                   // This is the file that is generated with the
                                                        // accessor methods inside.
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @AG\Generate                                     // Here you ask methods to be generated
     *
     */
    private int $id;

    // ...
}
```

The files will be generated in a subdirectory and namespace (Generated) relative to the current
file. This file can be included as trait.

### Specify which methods to generate

It is possible to disable generation of certain accessor methods by specifying them in
the annotation.

```php
/**
 * @AG\Generate(add="none",set="none",remove="none",get="none",is="none")
 */
```

`Is` is an alias for get. If your property is of type boolean an `isProperty` method is
generated instead of a `getProperty` method. For `ORM\GeneratedValue` properties, no
setters will be generated. **Note that the example above will generate no code at all.**

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

### Encryption key roll-over
In case the encryption keys ever need to be rotated, a fallback mechanism is available to minimize service interruption
while switching keys. Using the fallback logic, new values can be encrypted with a new public key while still being able
to decrypt both values encrypted with the old and values encrypted with the new public key.

The flow to roll-over encryption keys would be as follows:
- Generate a new private/public-key pair
- Store these in the paths specified in the composer.json as before
- Store the old private key somewhere next to it and specify it in the composer.json under `<encryption_alias>_fallback`
- After deploying, new values will be encrypted with the new public key and can be decrypted with the new private key,
  while old values are first tried to be decrypted with the new private key and when that fails, the old (fallback) 
  private key is used.
- Run a script to re-encrypt all values (`get()` the values and `set()` them again using the generated methods)
- When all values have been re-encrypted, the fallback key should be removed again.

Example composer.json config:
```
"extra": {
    "accessor-generator": {
        <encryption_alias>: {
            "public-key": <public_key_file>
            "private-key": <private_key_file>
        },
        <encryption_alias>_fallback: {
            "private-key": <fallback_private_key_file>
        }
    }   
...
```

## Parameters using ENUM classes

Since version 2.8.0, the support of accessor generation of parameterized collections has been added. With this addition,
the requirement of PHP 7.1 has been added due to the need of `ReflectionConstant`, which was added in PHP 7.1.

Imagine having an entity that holds an `ArrayCollection` to another entity that holds parameters. For example:
```php
$task = new Task();
$task->setParam(MyParamEnum::I_CLIENT_ID, 123456);

echo $task->getParam(MyParamEnum); 
// 12345
```

As you might notice, although the parameter name is prefixed with `I_` - which would indicate that we're dealing with
an integer - you can still set any data-type you want as long as the implementation of `setParam` supports it. If you're
working in a large team or in larger projects, not everybody might be aware that an enum class exists that defines all
common parameter names that _should_ be used throughout the application for this entity.

Version 2.8.0 introduces the ability to generate accessors for enum classes.

### Requirements

The owning entity - `Task` in the example above - must implement a property that is of type `ArrayCollection` which \
defines a `OneToMany` relationship with a `Parameter`-entity.

```php
class Task
{
    // ...
    
    /**
     * @ORM\OneToMany(targetEntity="Parameter", cascade={"persist"})
     */
    private $parameters;

    // ...
}
```

The `Parameter` entity must implement the following:
```php
use Hostnet\Component\AccessorGenerator\Enum\EnumeratorCompatibleEntityInterface;

class Parameter implements EnumeratorCompatibleEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Task")
     */
    private $owner;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     * @AG\Generate()
     */
    private $value;
    
    // This signature is a requirement for enum accessor generation.
    public function __construct($task, string $name, ?string $value)
    {
        $this->owner = $task;
        $this->name  = $name;
        $this->value = $value;
    }
}
```

### Enum class

The "enum class" only consists of public constants that use a prefix in their names to denote the data type of the
values they hold in the database.

The following types are supported:

| prefix | type    | example |
|--------|---------|---------|
| `S_`   | string  | "foobar"|
| `I_`   | integer | 1234    |
| `F_`   | float   | 3.14    |
| `B_`   | boolean | true    |
| `A_`   | array   | array   |

Now, lets take the following example for an enum class with some parameters:

```php
class MyTaskParamNames
{
    /**
     * Represents the client if the task is currently runnnig for.
     */
    public const I_CLIENT_ID = 'CLIENT_ID';
    
    /**
     * An awesome URL.
     */
    public const S_AWESOME_URL = 'https://www.hostnet.nl/';
}
```

Now that we have our three classes (`Task`, `Parameter` and `MyTaskParamNames`), we can start generating code.

### The "Enumerator" annotation

With version 2.8.0 comes the `Enumerator` annotation which can be used inside the existing `Generate` annotation.

> **Upgrading from 2.8.0 to 2.8.1:**
> The "name" setting in the annotation has been changed to "property" to be more consistent. Since 2.8.1, the ability
> to add inline enumerators through other class properties has been added. See below for more information.

Taking the code that we just wrote in the examples above, we can generate an accessor method for `MyTaskParamNames`
by modifying the annotation of the `parameters` property of our `Task` class.

```php
class Task
{
    use Generated\TaskMethodsTrait;

    /**
     * @ORM\OneToMany(targetEntity="Parameter", cascade={"persist"})
     * @AG\Generate(enumerators={
     *     @AG\Enumerator("MyTaskParamNames", property="my_params")
     * })
     */
    private $property;
    
    /**
     * @var Generated\MyTaskParamNamesEnum
     */
    private $my_params;
}
```

Once the code is generated, you will now have a newly generated class called `MyTaskParamNamesEnum` in the 
`Generated` directory (and namespace) relative to the namespace of `MyTaskParamNames`. An accessor for this class is
generated using the `property` settting in the `TaskMethodsTrait`.

The accessor for this enum based on the code above will be called `getMyParams()`. You can give this any name you want
as long as it is suitable for a method name.

Once the code is generated, you'll have access to 5 methods per parameter:
```php

$task = new Task();

// hasClientId() will check if a parameter with the name I_CLIENT_ID exists in the collection of parameters belonging to
// this Task instance.
if (! $task->getMyParams()->hasClientId()) {
    
    // Create the I_CLIENT_ID parameter with a value of 1234.
    $task->getMyParams()->setClientId(1234);  
}

// Update the value of the existng parameter.
$task->getMyParams()->setClientId(999);

// Retrieve the value
$client_id = $task->getMyParams()->getClientId();

// Clear the value (keeps the element in the collection, but nullifies the value).
// hasClientId() will now return FALSE as if the parameter doesn't exist.
$task->getMyParams()->clearClientId();

// Remove the element entirely, effectively dropping the record from the database.
$task->getMyParams()->removeClientId();
```

All methods are strictly typed based on their prefix in the enum class.

Have a look at the [ParamNameEnum](test/Generator/fixtures/expected/ParamNameEnum.php) class to
see an example of the generated code.

> **WARNING**: The default visibility of accessor methods (get/is/set/add/remove) will be set to 
> `none` if enumerators are used. If you still need these methods to be generated, you'll have to
> specify them explicitly.

### Multiple enumerators
As you might have noticed, the `enumerators` property of the `Generate` annotation accepts a list
of one or more `Enumerator` annotations. You can specify one ore more enum classes that utilize
the same collection for their "storage".

If your annotation looks like this:
```php
/**
 * @AG\Generate(enumerators={
 *     @AG\Enumerator("MyTaskParamNames", property="my_params"),
 *     @AG\Enumerator("BetterParamNames", property="better_params")
 * });
 */
 private $parameters;
 
 /**
  * @var Generated\MyTaskParamNamesEnum
  */
 private $my_params;
 
 /**
  * @var Generated\BetterParamNamesEnum
  */
 private $better_params;
```

The generator will now create two accessors for these parameter enumerators that you can use like
this:
```php
$task->getMyParams()->hasClientId();       // From MyTaskParamNames
$task->getBetterParams()->setFoobar(1234); // From BetterParamNames
```

### Separated enumerator accessor generation
You can also define enumerators outside the `@Generate` annotation. If used in combination with the `entity-plugin-lib`,
it is possible to define a `trait` that holds an enumerator property that refers to a collection on your entity.

Lets say we want to add an extra enumerator to our - already existing - Task entity that we have written before.

```php
use Hostnet\Component\AccessorGenerator\Annotation as AG;

trait TaskTrait
{
    use Generated\TaskTraitMethodsTrait;

    /**
     * @AG\Enumerator("\My\Namespace\MyExtraParamName", name="parameters")
     * @var \My\Namespace\Generated\MyExtraParamNameEnum
     */
    private $some_extra_params;
}
```
The `name` setting refers to the ArrayCollection property that holds all parameters owned by that entity.

Once the code is generated, you can now invoke the enumerator like any other:
```php
<?php

$task = new Task();
$task->getSomeExtraParams()->...

// Whilst still having access to the already existing enumerators that we defined before.
$task->getMyParams();
$task->getBetterParams();
```

Make sure to include the trait `Generated\TaskTrait` - or whatever name your entity has - in the task entity to make
this work. So,
```php
<?php
class Task
{
    use Generated\TaskMethodsTrait;
}
```
becomes:

```php
<?php
class Task
{
    use Generated\TaskTrait;
    use Generated\TaskMethodsTrait;
}
```
Please refer to the [entity-plugin-lib](https://github.com/hostnet/entity-plugin-lib) for more information.
