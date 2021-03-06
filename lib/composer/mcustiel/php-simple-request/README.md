php-simple-request
===============

php-simple-request is a library designed to simplify requests validation and filtering, that generates an object representation from the request data.

The idea behind this library is to design objects that represent the requests that the application receives, and use php-simple-request to map the request data to those objects and sanitize the received data. To do this, the library provides a set of annotations to specify the filters and validations to execute over the request data.

This library is optimized to be performant by using a cache system to save the parser classes generated by reading the annotations.

[![Build Status](https://travis-ci.org/mcustiel/php-simple-request.png?branch=master)](https://travis-ci.org/mcustiel/php-simple-request)
[![Coverage Status](https://coveralls.io/repos/mcustiel/php-simple-request/badge.svg?branch=master&service=github)](https://coveralls.io/github/mcustiel/php-simple-request?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mcustiel/php-simple-request/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mcustiel/php-simple-request/?branch=master)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b5d7f290-ab06-4035-8050-f24d9465bb06/big.png)](https://insight.sensiolabs.com/projects/b5d7f290-ab06-4035-8050-f24d9465bb06)

Table of contents
-----------------

- [Installation](#installation)
    - [Composer](#composer)
- [How to use it?](#how-to-use-it)
    - [Define objects to represent the requests](#define-objects-to-represent-the-requests)
    - [Parse the request and get an object representation](#parse-the-request-and-get-an-object-representation)
    - [Sub-objets](#sub-objects)
    - [Caching](#caching) 
- [Filters](#filters)
    - [Capitalize](#capitalize)
    - [CustomFilter](#customfilter)
    - [DefaultValue](#defaultvalue)
    - [Lowercase](#lowercase)
    - [RegexReplace](#regexreplace)
    - [StringReplace](#stringreplace)
    - [ToFloat](#tofloat)
    - [ToInteger](#tointeger)
    - [Trim](#trim)
    - [Uppercase](#uppercase)
- [Validators](#validators)
    - [AllOf](#allof)
    - [Alpha](#alpha)
    - [AlphaNumeric](#alphanumeric)
    - [AnyOf](#anyof)
    - [CustomValidator](#customvalidator)
    - [DateTime](#datetime)
    - [DateTimeFormat](#datetimeformat)
    - [Definition](#definition)
    - [Email](#email)
    - [Enum](#enum)
    - [ExclusiveMaximum](#exclusivemaximum)
    - [ExclusiveMinimum](#exclusiveminimum)
    - [Hexa](#hexa)
    - [HostName](#hostname)
    - [IPV4](#ipv4)
    - [IPV6](#ipv6)
    - [Items](#items)
    - [MacAddress](#macaddress)
    - [Maximum](#maximum)
    - [MaxItems](#maxitems)
    - [MaxLength](#maxlength)
    - [MaxProperties](#maxproperties)
    - [Minimum](#minimum)
    - [MinItems](#minitems)
    - [MinLength](#minlength)
    - [MinProperties](#minproperties)
    - [MultipleOf](#multipleof)
    - [Not](#not)
    - [NotEmpty](#notempty)
    - [NotNull](#notnull)
    - [OneOf](#oneof)
    - [Pattern](#pattern)
    - [Properties](#properties)
    - [RegExp](#regexp)
    - [Required](#required)
    - [TwitterAccount](#twitteraccount)
    - [Type](#type)
    - [TypeFloat](#typefloat)
    - [TypeInteger](#typeinteger)
    - [UniqueItems](#uniqueitems)
    - [Uri](#uri)

Installation
------------

#### Composer:

This project is published in packagist, so you just need to add it as a dependency in your composer.json:

```javascript
    "require": {
        // ...
        "mcustiel/php-simple-request": "*"
    }
```

If you want to access directly to this repo, adding this to your composer.json should be enough:

```javascript  
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mcustiel/php-simple-request"
        }
    ],
    "require": {
        "mcustiel/php-simple-request": "dev-master"
    }
}
```

Or just download the release and include it in your path.

How to use it?
--------------

#### Define objects to represent the requests

First of all, you have to define the classes that represent the requests you expect the application to receive. The setter methods of this class will be used by php-simple-request to write the values obtained from the request into the object.

```php
namespace Your\Namespace;

class PersonRequest 
{
    private $firstName;
    private $lastName;
    private $age;
    
    // getters and setters (setters are required by the library)
}
```

Then, you can specify the filters you want to apply on each field:

```php
namespace Your\Namespace;
use Mcustiel\SimpleRequest\Annotation\Filter\Trim;
use Mcustiel\SimpleRequest\Annotation\Filter\UpperCase;

class PersonRequest 
{
    /**
     * @Trim
     */
    private $firstName;
    /**
     * @Trim
     * @UpperCase
     */
    private $lastName;
    private $age;
    
    // getters and setters (setters are required by the library)
}
```

And also the validations you want to run for each property value:

```php
namespace Your\Namespace;
use Mcustiel\SimpleRequest\Annotation\Filter\Trim;
use Mcustiel\SimpleRequest\Annotation\Filter\UpperCase;
use Mcustiel\SimpleRequest\Annotation\Validator\NotEmpty;
use Mcustiel\SimpleRequest\Annotation\Validator\MaxLength;
use Mcustiel\SimpleRequest\Annotation\Validator\Integer;

class PersonRequest 
{
    /**
     * @Trim
     * @NotEmpty
     */
    private $firstName;
    /**
     * @Trim
     * @UpperCase
     * @NotEmpty
     * @MaxLength(32)
     */
    private $lastName;
    /**
     * @Integer
     */
    private $age;
    
    // getters and setters (setters are required by the library)
}
```

**Note**: php-simple-request executes the filters first and then it executes the validations.

#### Parse the request and get an object representation

To parse the request and convert it to your object representation, just receive the request using the RequestBuilder object (the field names in the request must have the same name to the fields in the class you defined). You must call the parseRequest method with an array or an object of type \stdClass. See an example:

```php
use Mcustiel\SimpleRequest\RequestBuilder;
use Your\Namespace\PersonRequest;
use Mcustiel\SimpleRequest\Exceptions\InvalidRequestException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Mcustiel\SimpleRequest\ParserGenerator;
use Mcustiel\SimpleRequest\Services\PhpReflectionService;
use Mcustiel\SimpleRequest\Services\DoctrineAnnotationService;
use Mcustiel\SimpleRequest\Strategies\AnnotationParserFactory;
use Mcustiel\SimpleRequest\FirstErrorRequestParser;

$requestBuilder = new RequestBuilder(
    new FilesystemAdapter(),
    new ParserGenerator(
        new DoctrineAnnotationService(),
        new AnnotationParserFactory(),
        new PhpReflectionService
    )
);

try {
    $personRequest = $requestBuilder->parseRequest($_POST, PersonRequest::class);
} catch (InvalidRequestException $e) {
    die("The request is invalid: " . $e->getMessage());
}
// Now you can use the validated and filtered personRequest to access the requestData.
```

If your request is received as a subarray of POST, just specify the key:

```php
$personRequest = $requestBuilder->parseRequest($_POST['person'], PersonRequest::class);
```

Also it can be used for some REST json request:

```php
$request = file_get_contents('php://input');
$personRequest = $requestBuilder->parseRequest(json_decode($request, true), PersonRequest::class, , new FirstErrorRequestParser());
```

The previous behaviour throws an exception when it finds an error in the validation. 
There is an alternative behaviour in which you can obtain a list of validation errors, one for each invalid field. To activate this alternative behavior, you have to specify it in the call to `RequestBuilder::parseRequest` like this:

```php
use Mcustiel\SimpleRequest\RequestBuilder;
use Your\Namespace\PersonRequest;
use Mcustiel\SimpleRequest\Exceptions\InvalidRequestException;
use Mcustiel\SimpleRequest\AllErrorsRequestParser;

$requestBuilder = new RequestBuilder();

try {
    $personRequest = $requestBuilder->parseRequest(
        $_POST, 
        PersonRequest::class,
        RequestBuilder::RETURN_ALL_ERRORS_IN_EXCEPTION
    );
} catch (InvalidRequestException $e) {
    $listOfErrors = $e->getErrors();   // This call returns only one error for the default behavior
}
// Now you can use the validated and filtered personRequest to access the requestData.
```

**Note:** InvalidRequestException::getErrors() is available for default behaviour too, it returns an array with only one error. 

#### Sub-objects

php-simple-request also allows you to specify the class to which parse a property's value using the annotation ParseAs. It's better to see it in an example:

Let's say we want to create a Couple class that contains two objects of type Person, which represent the members of the couple. To specify that the properties must be parsed as Persons we use ParseAs.

```php
use Mcustiel\SimpleRequest\Annotation as SRA;

class CoupleRequest
{
    /**
     * @SRA\Validator\DateTimeFormat("Y-m-d")
     */
    private $togetherSince;
    /**
     * @SRA\ParseAs("\Your\Namespace\PersonRequest")
     */
    private $person1;
    /**
     * @SRA\ParseAs("\Your\Namespace\PersonRequest")
     */
    private $person2;
    
    //... Getters and setters (setters are required by the library)
}
``` 

php-simple-request will automatically convert the value received in the fields person1 and person2 from the request into the type PersonRequest.

**Note:** If a property has the ParseAs annotation and also validations and filters, php-simple-request will first execute parseAs and then filters and validations as usual.

#### Array of objects:

If you plan to parse not an object, but an array of objects, you can tell php-simple-request to do this using the array notation by putting the class name as the first element of an array:

```
try {
    $persons = $requestBuilder->parseRequest(
        $_POST['form_data'], 
        [PersonRequest::class],
        RequestBuilder::RETURN_ALL_ERRORS_IN_EXCEPTION
    );
    // Now $persons is an array containing PersonRequest objects.
} catch (InvalidRequestException $e) {
    $listOfErrors = $e->getErrors();   // This call returns only one error for the default behavior
}
```

#### Caching:

As the request class definition uses annotations to specify filters and validators, it generates a lot of overhead when parsing all those annotations and using reflection. To avoid this overhead, php-simple-request supports the use of PSR-6 Cache. Just pass the implementation as the first argument to create the `RequestBuilder` object:

```php
$requestBuilder = new RequestBuilder(
    new AnyPsr6PoolAdapter(),
    new ParserGenerator(new AnnotationReader(), new AnnotationParserFactory())
);
```  

You can pass a NullObject implementation for testing purposes.

Filters
-------

#### Capitalize

This filter sets all the string characters to lowercase but its first character, which is converted to uppercase. This annotation accepts a boolean specifier value to define wether to capitalize just the first letter of the first word or the first letter of all words in the string.  

```php
/**
 * @Capitalize
 */
private $name;
// Will convert, for instance, mariano to Mariano.
/**
 * @Capitalize(true)
 */
private $fullName;
// Will convert, for instance, mariano custiel to Mariano Custiel.
```

#### CustomFilter

This is a special filter annotation that allows you to specify your own filter class and use it to filter the value in the field. It accepts two parameters: 
* value: which is the specifier.
* class: which is your custom filter class (it must implement Mcustiel\SimpleRequest\Interfaces\FilterInterface

```php
/**
 * @CustomFilter(class="Vendor\\App\\MyFilters\\MyFilter", value="yourSpecifier")
 */
private $somethingHardToFilter;
// Will call Vendor\\App\\MyFilters\\MyFilter::filter($value) using "yourSpecifier".
```

#### DefaultValue

Sets a default value when the received value is null or an empty string.

```php
/**
 * @DefaultValue("I am a default value")
 */
private $thisCanHaveADefault;
```

#### LowerCase

LowerCase filter converts all characters in the given string to lowercase.

#### RegexReplace

Executes a replace in the string, using a regular expression pattern as a search. 
It accepts two parameters: 
* pattern: The regular expression to search for.
* replacement: The replacement text for the matches.

```php
/**
 * @RegexReplace(pattern="/[^a-z0-9_]/i", replacement="_")
 */
private $onlyAlnumAndUnderscores;
// Will replace all non alphanumeric characters with underscores.
```

#### StringReplace

Executes a replace in the string, using a string pattern as a search. 
It accepts two parameters: 
* pattern: The string to search for.
* replacement: The replacement text for the matches.

**NOTE:** This method uses str_replace internally, you can take advantage of it by setting pattern to array, etc.
[See str_replace specification](http://php.net/manual/en/function.str-replace.php).


```php
/**
 * @StringReplace(pattern="E", replacement="3")
 */
private $whyAmIChangingThis;
// Will replace all non E with 3.
```

#### ToFloat

This filters just forces a cast to float of the received value.

#### ToInteger

Analog to Float, forces a cast to integer to the received value.

#### Trim

Trims the string from both ends.

#### UpperCase

Converts all characters in the given string to uppercase.

Validators
----------

The validators marked with an **(*)** behave similar to json-schema defined validators. Please see: [JSON Schema definition](http://json-schema.org/latest/json-schema-validation.html) and [understanding JSON Schema](spacetelescope.github.io/understanding-json-schema/).

#### AllOf*

This validator receives a list of validators as parameter and checks that all of them matches the value. You can obtain the same behavior just by adding multiple Valitor annotations for that property.

##### Example:
```php
/**
 * @AllOf({@Integer, @Minimum(0), @Maximum(100)})
 */
private $percentage;
// Will match an integer between 0 and 100.
```

#### Alpha

This validator checks that all characters in a string are in the range A-Z or a-z.

##### Example:
```php
/**
 * @Alpha
 */
private $onlyLetters;
// Will match a string containing only letters.
```

#### AlphaNumeric

This validator checks that all characters in a string are in the range A-Z or a-z or 0-9.

##### Example:
```php
/**
 * @AlphaNumeric
 */
private $lettersAndNumbers;
// Will match a string containing only alphanumeric characters.
```

#### AnyOf*

This validator receives a list of validators as parameter and checks if at least one of them validates a given value. 

##### Example:
```php
/**
 * @AnyOf({@Integer, @IPV6})
 */
private $integerOrIpv6;
// Will match an integer or an IPV6.
```

#### CustomValidator

This is a special validator annotation that allows you to specify your own validator class and use it to validate the value in the field. It accepts two parameters: 
* value: which is the specifier.
* class: which is your custom filter class (it must implement Mcustiel\SimpleRequest\Interfaces\ValidatorInterface

##### Example:
```php
/**
 * @CustomValidator(class="Vendor\\App\\MyValidators\\MyValidator", value="yourSpecifier")
 */
private $somethingHardToCheck;
// Will call Vendor\\App\\MyValidators\\MyValidator::validate($value) using "yourSpecifier".
```

#### DateTime*

This validator checks that the given string is a date and its format is compatible with \DateTime::RFC3339 (Y-m-d\TH:i:sP)

##### Example:
```php
/**
 * @DateTime
 */
private $dateTime;
// Matches 2005-08-15T15:52:01+00:00
```

**Default specifier value:** \DateTime::ISO8601

#### DateTimeFormat

This validator checks that the given string is a date and its format is compatible with the specified date format. The format to specify as the annotation value must be compatible with the php method \DateTime::createFromFormat.

##### Example:
```php
/**
 * @DateTimeFormat("M d, Y")
 */
private $dayOfBirth;
// Matches Oct 17, 1981
```

**Default specifier value:** \DateTime::ISO8601

#### Definition*

This validator is an alias for CustomValidator.

#### Email

This validator checks that the given value is a string containing an email. This annotation expects no value specifier.

##### Example:
```php
/**
 * @Email
 */
private $email;
```

#### Enum*

This validator checks that the given value is in a specified collection of values.

##### Example:
```php
/**
 * @Enum('value1', 'value2', 'value3')
 */
private $enum;
// Will match the strings value1, value2 or value3.
```

#### ExclusiveMaximum*

This validator checks that the given value is lower than the specified one.

##### Example:
```php
/**
 * @ExclusiveMaximum(0)
 */
private $negativeReals;
// Will match any number < 0.
```

#### ExclusiveMinimum*

This validator checks that the given value is greater than the specified one.

##### Example:
```php
/**
 * @ExclusiveMinimum(0)
 */
private $age;
// Will match any number > 0.
```

#### Hexa

This validator checks that the given value is a string containing only hexadecimal characters (ranges 0-9, a-f, A-F).

##### Example:
```php
/**
 * @Hexa
 */
private $htmlColor;
```

#### Hostname

Checks if the value is a hostname.

##### Example:
```php
/**
 * @Hostname
 */
private $responseDomain;
```

#### TypeFloat

This validator checks that the given value is a float. A boolean modifier can be specified in this annotation, indicating if the value must be a strict float or if integers can be validated as floats too.

##### Example:
```php
/**
 * @Float
 */
private $meters;
// accepts 1, 1.1, etc.

/**
 * @Float(true)
 */
private $meters;
// accepts 1.0, 1.1, etc.
```

**Default specifier value:** false, indicating that integers are validated as floats

#### TypeInteger

This validator checks that the given value is numeric and it's an integer. It expects a boolean modifier, strict, that indicates if integers in float format like 1.0, 2.0, etc. should be validated as integers. Default strict value: true, meaning that no float format accepted.

##### Examples:
```php
/**
 * @Integer
 */
private $seconds;
// accepts 1, 2, -3, 0, etc.
```

```php
/**
 * @Integer(false)
 */
private $majorVersion;
// accepts 1, 2.0, 3, etc.
```

#### IPV4

This validator checks that the given value is a valid IPv4. It does not expect any modifier.

##### Example:
```php
/**
 * @IPV4
 */
private $ip;
// accepts 0.0.0.0, 255.255.255.255, etc.
```

#### IPV6

This validator checks that the given value is a valid IPv6. It does not expect any modifier.

##### Example:
```php
/**
 * @IPV6
 */
private $ip;
// accepts ::A000:A000, A000::A000, A000::A000::, 2001:0000:3238:DFE1:63:0000:0000:FEFB, etc.
```

#### Items*

This validator checks that each element of a given array matches a specified set of validators in its corresponding index. It expects two parameters: items and additionalItems. Items contains the validations for the array, it can be a validator (in which case every element must match it) or an array of validators (in which case each element must match the validator in the same position); its default value is an empty array. AdditionalItems can be a boolean or a validator; if it's a boolean true, items without a validator in the same position will not be checked, if it false it will not accept values without validator in the same position and if it's a validator, all items without a validator in the same position must match it. For a detailed and good description please see the aforementioned documents about JSON Schema. 
**Default specifier values:**
* items = []
* additionalItems = true

```php
/**
 * @Items(items=@Integer, additionalItems=true)
 */
private $arrayOfInt;
// accepts Arrays of int of any size.
```

#### MacAddress*

Validates that the value is a string specifying a MAC address.

##### Example:
```php
/**
 * @MacAddress
 */
private $mac;
```

#### Maximum*

This validator checks that the given value is lower than or equal to the specified one.

##### Example:
```php
/**
 * @Maximum(0)
 */
private $negativeRealsOrZero;
// Will match any number <= 0.
```

#### MaxItems*

This validator checks that the field's is an array and it has an amount of items equal to or less than the specification. The specification value must be an integer greater than 0. The field must be an array.

##### Example:
```php
/**
 * @MaxItems(3)
 */
private $stoogesOnScreen;
// accepts [], ['curly'], ['curly', 'larry'] and ['curly', 'larry', 'moe'].
```

#### MaxLength*

This validator checks that the field's length is equal to or less than the specification. The specification value must be an integer. The field must be a string.

##### Example:
```php
/**
 * @MaxLength(4)
 */
private $pin;
// accepts empty string, 1, 12, 123 and 1234.
```

**Default specifier value:** 255

#### MaxProperties*

This annotation validates that a given array or stdClass contains, at most, the specified number of items or properties. It's analog to MaxItems.

##### Example:
```php
/**
 * @MaxProperties(3)
 */
private $stoogesOnScreen;
// accepts (stdClass) [], (stdClass)['stooge1'->'curly'], (array)['stooge1'=>'curly', 'stooge2'=>'larry'], ['curly', 'larry', 'moe'].
```

#### Minimum*

This validator checks that the given value is greater than or equal to the specified one.

##### Example:
```php
/**
 * @Minimum(-273)
 */
private $temperatureInCelsius;
// Will match any number >= -273.
```

#### MinItems*

This validator checks that the field's is an array and it has an amount of items equal to or greater than the specification. The specification value must be an integer greater than 0. The field must be an array.

##### Example:
```php
/**
 * @MinItems(2)
 */
private $players;
// accepts ['alice', 'bob'], ['a' => 'alice', 'b' => 'bob'], ['alice', 'bob', 'carol'].
```

#### MinLength*

This validator checks that the field's length is equal to or greater than the specification. The specification value must be an integer. The field can be a string or an array.

##### Example:
```php
/**
 * @MinLength(8)
 */
private $password;
// accepts 'password', 'password1', 'password1234' and all those very secure passwords.
```

**Default specifier value:** 0

#### MinProperties*

This validator checks that the field's is an array or a stdClass and it has an amount of items equal to or greater than the specification. The specification value must be an integer greater than 0.

##### Example:
```php
/**
 * @MinProperties(2)
 */
private $players;
// accepts ['a' => 'alice', 'b' => 'bob'], ['alice', 'bob', 'carol'], stdclass(a->'alice', 'b'->'bob');
```

#### MultipleOf*

Validates that the value received is multiple of the specified number.
 
##### Example:
```php
/**
 * @MultipleOf(2)
 */
private $evenNumber;
// accepts 8, 20, 100, etc.
```

#### Not*

Checks that the value is not valid against a specified validator.
 
##### Example:
```php
/**
 * @Not(@MultipleOf(2))
 */
private $oddNumber;
// accepts 3, 15, 97, etc.
```

#### NotEmpty

This validator checks that the field's is not empty. Internally, this validator uses php's empty so the functionality is exactly the same. It does not expect any modifier.

##### Example:
```php
/**
 * @NotEmpty
 */
private $password;
// accepts 1, 'A', ['a'], etc.
```

**Default specifier value:** 0

#### NotNull

This validator checks that the field's is not null, it can be used to check if the field is present in the request also. Use this function only if you want to check the value is present and you accept empty values in the field; if you will not accept empty values, just use NotEmpty validator which also checks values is not null. It does not expect any modifier.

##### Example:
```php
/**
 * @NotNull
 */
private $mandatoryField;
// accepts '', 0, [], 1, 'A', ['a'], etc.
```

#### OneOf*

This validator receives a list of validators as parameter and checks that exactly one of them validates against a given value.

##### Example:
```php
/**
 * @AnyOf(@Integer, @IPV6)
 */
private $integerOrIpv6;
// Will match an integer 'xor' an IPV6.
```

#### Pattern*

Alias for RegExp validator.

#### Properties*

Analog to items, but works with objects of type stdClass or associative arrays. It runs a list of validators through the properties of a stdclass or the items in an associative array, using the properties names or patterns. Please see the aforementioned documents about json-schema.

**Default specifier values:**
* properties = []
* patternProperties = []
* additionalProperties = true

##### Example:
```php
/**
 * @Items(properties={"name", @NotEmpty, "age", @Numeric}, additionalProperties=true)
 */
private $person;
// accepts Arrays or objects containing a not empty name property and a numeric age property. Can contain other properties (because additionalProperties is true)
```

**Note:** This annotation was added as a json-schema-like annotation. Please have in mind that it could be better to use sub-object creation through ParseAs annotation in most of the cases.

#### RegExp

This validator checks the field against a given regular expression.

##### Example:
```php
/**
 * @RegExp("/[a-z]*/i")
 */
private $onlyAlpha;
// accepts '', 'a', 'A', 'ab', etc.
```

#### Required*

This validator checks that an object of type stdClass or an associative array contains the list of keys specified.

##### Example:
```php
/**
 * @Required({"name", "age", "sex"})
 */
private $person;
// accepts ['name' => 'Alice', 'age' => 28, 'sex' => 'f', 'lastName' => 'Smith' ]
```

#### TwitterAccount

This validator checks that the field contains a twitter account.

##### Example:
```php
/**
 * @TwitterAccount
 */
private $twitterAccount;
// accepts '@user', '@user_name_1', etc.
```

#### Type*

Validates that the received value is of the type specified. The specified type must be one of the types defined by json-schema: 'array', 'object', 'integer', 'number', 'string', 'boolean', 'null'.

##### Example:
```php
/**
 * @Type("array")
 */
private $iCanOnlyBeAnArray;
```

#### UniqueItems*

Validates that the received value is an array containing all unique values.

##### Example:
```php
/**
 * @UniqueItems
 */
private $noRepeatedValues;
```

#### Uri*

This validator checks that the field contains a valid URL.

##### Example:
```php
/**
 * @Uri
 */
private $webpage;
// accepts 'localhost', 'www.server.com', 'http://www.webserver.com/page.php?t=1#anchor', etc
```
