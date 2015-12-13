# JSON assertions for PHPUnit

## Author and copyright

Martin Helmich <kontakt@martin-helmich.de>  
This library is [MIT-licensed](LICENSE.txt).

## Installation

    $ composer require helmich/phpunit-json-assert

## Usage

Simply use the trait `Helmich\JsonAssert\JsonAssertions` in your test case. This
trait offers a set of new `assert*` functions that you can use in your test
cases:

```php
<?php
use Helmich\JsonAssert\JsonAssertions;

class MyTestCase extends PHPUnit_Framework_TestCase
{
  use JsonAssertions;

  public function testJsonDocumentIsValid()
  {
    $jsonDocument = [
      'id'          => 1000,
      'username'    => 'mhelmich',
      'given_name'  => 'Martin',
      'family_name' => 'Helmich',
      'age'         => 27,
      'hobbies'     => [
        "Heavy Metal",
        "Science Fiction",
        "Open Source Software"
      ]
    ];

    $this->assertJsonValueEquals($jsonDocument, '$.username', 'mhelmich');
    $this->assertJsonValueEquals($jsonDocument, '$.hobbies[*]', 'Open Source Software');
  }
}
```

Most assertions take a `$jsonPath` argument which may contain any kind of
expression supported by the [JSONPath][jsonpath] library.

## Assertion reference

##### `assertJsonValueEquals($doc, $jsonPath, $expected)`

Asserts that the JSON value found in `$doc` at JSON path `$jsonPath` is equal
to `$expected`.

##### `assertJsonValueMatches($doc, $jsonPath, PHPUnit_Framework_Constraint $constraint)`

Asserts that the JSON value found in `$doc` at JSON path `$jsonPath` matches
the constraint `$constraint`.

Example:

```php
$this->assertJsonVauleMatches(
  $jsonDocument,
  '$.age',
  PHPUnit_Framework_Assert::greaterThanOrEqual(18)
);
```

##### `assertJsonDocumentMatches($doc, $constraints)`

Asserts that a variable number of JSON values match a constraint. `$constraints`
is a key-value array in which JSON path expressions are used as keys to a
constraint value.

Example:

```php
$this->assertJsonDocumentMatches($jsonDocument, [
    '$.username' => 'mhelmich',
    '$.age'      => PHPUnit_Framework_Assert::greaterThanOrEqual(18)
]);
```

[jsonpath]: https://packagist.org/packages/flow/jsonpath
