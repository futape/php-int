#Int

A PHP library emulating integers without any size limits.



##Table of contents

1.  [API](#api)
2.  [System requirements](#system-requirements)
3.  [A word about the master branch](#a-word-about-the-master-branch)
4.  [License](#license)
5.  [Support](#support)
6.  [Contributing](#contributing)
7.  [Author](#author)



##API

The library's functions are available via the `Int` class. That class is defined in the `futape\int` namespace.  
It can be included the following way.

    require_once "<path-to-src>/futape/int/Int.php";
    
    use futape\int\Int;

where `<path-to-src>` is the path of the directory you have placed the Int source in.

###Instance functions

####__construct()

`void __contruct( string $number )`

The `Int` class's constructor, called when a new instance of this class is created.  
Validates the passed number string and initiates the object's properties.  
The passed string must begin with an optional `+` or `-`, followed by at least one digit and an optional *decimal part* consisting of a dot (the decimal point) and one or more digits. If a decimal part is specified, the preceding digits may be skipped, assuming a value of 0 instead. No other characters may be included in the string.  
Since this is an *interger* library and not a float library, the decimal part is ignored entirely.  
A leading `-` character indicated a negative number. Otherwise, a positive one is assumed.  
If the passed string doesn't pass the validation, an [`InvalidArgumentException`](http://php.net/manual/en/class.invalidargumentexception.php) is thrown.

####__toString()

`string __toString()`

This function is called when an `Int` object is casted as a string, for example when it's concatenated with another string. Instead of the object itself, the returned value is used.

Returns the object's [`get()`](#) method's return value.

####get()

`string get()`

Returns the value of the object as a string.

####getAbs()

`string getAbs()`

Returns the absolute value of the object as a string.

####isNeg()

`bool isNeg()`

Checks whether the current value is negative (i.e. it's lower than 0).

If the current object's value is negative, `true` is returned, otherwise `false` is returned.

####eq()

`bool eq( Int|string $compare )`

Checks whether the current value equals the passed one.  
This is equal to [`$int->cmp($compare)==0`](#).

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the current object's value is equal to the passed one, `true` is returned, otherwise `false` is returned.

####gt()

`bool gt( Int|string $compare )`

Checks whether the current value is greater than the passed one.  
This is equal to [`$int->cmp($compare)>0`](#).

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the current object's value is greater than the passed one, `true` is returned, otherwise `false` is returned.

####gte()

`bool gte( Int|string $compare )`

Checks whether the current value is greater than or equal to the passed one.

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the current object's value is greater than or equal to the passed one, `true` is returned, otherwise `false` is returned.

####lt()

`bool lt( Int|string $compare )`

Checks whether the current value is lower than the passed one.  
This is equal to [`$int->cmp($compare)<0`](#).

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the current object's value is lower than the passed one, `true` is returned, otherwise `false` is returned.

####lte()

`bool lte( Int|string $compare )`

Checks whether the current value is lower than or equal to the passed one.

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the current object's value is lower than or equal to the passed one, `true` is returned, otherwise `false` is returned.

####add()

`void add( Int|string $summand )`

Adds the passed object's value to the current one.

+   `$summand`: The number to add to the current value. If not already an `Int` object, it's turned into one.

####sub()

`void sub( Int|string $subtrahend )`

Subtracts the passed object's value from the current one.

+   `$subtrahend`: The number to subtract from the current value. If not already an `Int` object, it's turned into one.

####mult()

`void mult( Int|string $multiplier )`

Multiplies the current value with the passed one.

+   `$multiplier`: The number to multiply the current value with. If not already an `Int` object, it's turned into one.

####pow()

`void pow( Int|string $exponent )`

Executes a exponentiation operation on the current value using the passed one as exponent.

+   `$exponent`: A number specifying the power to which to raise the current value to. If not already an `Int` object, it's turned into one.

####sq()

`void sq()`

Short for [`$int->pow("2")`](#).  
Raises the current value to the power of 2.

####div()

`void div( Int|string $divisor )`

Divides the current value by the passed one.

+   `$divisor`: The number by which to divide the current value. If not already an `Int` object, it's turned into one.  
    If 0, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.

####mod()

`void mod( Int|string $divisor )`

Executes a modulus operation on the current value using the passed one as divisor.

+   `$divisor`: The number by which to divide the current value before getting the remainder. If not already an `Int` object, it's turned into one.  
    See [`div()`](#) for more information.

####calc()

`void calc( string $operator, Int|string $number )`

Calls the method that corresponds to the specified arithmetic operator.  
The following operators are supported.

+   `+` ([`Int::CALC_ADD`](#)): Addition 
+   `-` ([`Int::CALC_SUB`](#)): Subtraction 
+   `*` ([`Int::CALC_MULT`](#)): Multiplication 
+   `**` ([`Int::CALC_POW`](#)): Exponentiation 
+   `/` ([`Int::CALC_DIV`](#)): Division 
+   `%` ([`Int::CALC_MOD`](#)): Modulus 

For any other operator an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.

+   `$operator`: The operator specifying which method to call. Use one of the operators shown above or use one of the `Int::CALC_*` constants.
+   `$number`: The number to calculate with. The value is passed to the corresponding function which in turn converts is into an `Int` object if it isn't already one.

####abs()

`void abs()`

Makes the object's value an absolute one by removing its sign.

####cmp()

`int cmp( Int|string $compare )`

Compares the current value to the passed one.

+   `$compare`: The object to compare the current one against. If not already an `Int` object, it's turned into one.

If the values are identical, `0` is returned, if the current one is greater than the passed one, `1` is returned, and if the it's lower than the passed one, `-1` is returned.

###Static functions

####min()

`Int|null min( [ Int|string|(Int|string)[] $int1 [, Int|string|(Int|string)[] $int2 [, ... ]]] )`

Searches for the lowest value in the passed ones.

+   `$int1, $int2, ...`: The values in which to search for the lowest one. Arrays passed to this function are merged together with the other parameters' values. String values are turned into a new `Int` object.

Returns the lowest value as an `Int` object or `null` if no values or empty arrays only have been passed to this function.

####max()

`Int|null max( [ Int|string|(Int|string)[] $int1 [, Int|string|(Int|string)[] $int2 [, ... ]]] )`

Searches for the highest value in the passed ones.

+   `$int1, $int2, ...`: The values in which to search for the highest one. Arrays passed to this function are merged together with the other parameters' values. String values are turned into a new `Int` object.

Returns the highest value as an `Int` object or `null` if no values or empty arrays only have been passed to this function.

####rand()

`Int rand( Int|string $max [, Int|string $min = "0" ] )`

Returns a random number in the range between `$min` and `$max`.

+   `$max`: The highest number (inclusive) the random number can be. If not already an `Int` object, it's turned into one.  
    If lower than `$min` or if negative, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.
+   `$min`: The lowest number (inclusive) the random number can be. If not already an `Int` object, it's turned into one.  
    If negative, an [`Exception`](http://php.net/manual/en/class.exception.php) is thrown.

Returns the generated random integer as an `Int` object.

###Constants

####CALC_ADD

`string CALC_ADD`

The arithmetic operator for additions.

####CALC_SUB

`string CALC_SUB`

The arithmetic operator for subtractions.

####CALC_MULT

`string CALC_MULT`

The arithmetic operator for multiplications.

####CALC_POW

`string CALC_POW`

The arithmetic operator for exponentiations.

####CALC_DIV

`string CALC_DIV`

The arithmetic operator for divisions.

####CALC_MOD

`string CALC_MOD`

The arithmetic operator for moduli.



##System requirements

Int is compatible with PHP 5.3+.

The following versions of PHP have been tested.

<table>
    <tbody>
        <tr>
            <td>5.3.29</td>
            <td>&#x2714;</td>
        </tr>
    </tbody>
</table>

Moreover, it executes *real* calculations with numbers up to 89.



##A word about the `master` branch

This repository has two main branches, the `develop` branch and the `master` branch.  
Branch management is done using [Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)'s branching model, meaning that all bleeding-edge features are available on the `develop` branch, while the `master` branch contains the stable releases only. Commits on the `master` branch introducing changes to the public API are tagged with a version number.

Versioning is done using [semantic versioning](http://semver.org/). This means that a version identifier consists of three parts, the first one being the *major* version number, the second one the *minor* version number and the third one speciying the *patch* number, separated by dots. Whenever a API-incompatible change is introduced, the major version is number increased. If the change is backwards-compatible to the public API, the minor version number is increased. A hotfix to the source increases the patch number.

A list of releases can be seen [here](https://github.com/futape/php-int/releases). Please note, that releases with a major version number of 0 belong to the initial development phase and are not considered to be absolutely stable. However, every release since version 1.0.0 is considered to be stable.



##License

The Int source is published under the permissive [*New* BSD License](http://opensource.org/licenses/BSD-3-Clause).  
A [copy of that license](https://github.com/futape/php-int/blob/master/src/futape/int/LICENSE) is located under `src/futape/int`.

Any other content is, if not otherwise stated, published under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).  
<a href="http://creativecommons.org/licenses/by/4.0/"><img alt="Creative Commons License" border="0" src="https://i.creativecommons.org/l/by/4.0/80x15.png" /></a>



##Support

<a href="https://flattr.com/submit/auto?user_id=lucaskrause&amp;url=http%3A%2F%2Fphp-int.futape.de" target="_blank"><img src="http://button.flattr.com/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0"></a>



##Contributing

Contributing to this project is currently not available.



##Author

<table><tbody><tr><td>
    <img src="http://www.gravatar.com/avatar/118bcae2fda8b302155ad47a2bfda556.png?s=100&amp;d=monsterid" />
</td><td>
    Lucas Krause (<a href="https://twitter.com/futape">@futape</a>)
</td></tr></tbody></table>

For a full list of contributors, click [here](https://github.com/futape/php-int/graphs/contributors).
