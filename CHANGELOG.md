# Changelog

All notable changes to `Config` will be documented in this file

## 3.2.0 - 2024-12-09

### Added
* PHP 8.3 and 8.4 to the build matrix (#164)

### Fixed
* Fatal error when root is null in has() method (#159 and #160)
* PHP 8.4 deprecations implicitly marking parameter as nullable is deprecated (#163)


## 3.1.0 - 2022-12-20

### Added
* PHP 8.2 to the build matrix (#154)

### Fixed
* Use assertEquals instead of assertSame (#153)
* Namespaces in tests (#154)

### Removed
* .travis.yml file (#152)
* support for the end of life PHP versions (#144)


## 3.0.1 - 2022-03-26

### Added
- Fix merge method (#145 and #147)

## 3.0.0 - 2021-12-30

### Added
- Move CI to GitHub actions (#132, #136 and #137)
- PHP 8.0 support, adding it to the build matrix (#138 and #139)
- PHP 8.1 support, adding it to the build matrix (#135, #140 and #141)

## 2.2.0 - 2020-12-07

### Added
- Serialization support (#127)
- Support for Properties files (#128)

### Fixed
- Test enhancement (#126)
- Typehint on Xml parser, parse method (#130)

## 2.1.0 - 2019-09-01

### Added
- Support for writing configuration back to file and string (#122)

## 2.0.2 - 2019-04-06

### Fixed
- Implementations of `ParserInterface` and cleanup (#120)
- Tests for PHP 7

## 2.0.1 - 2019-02-02

### Fixed
- Parsing PHP file (#114)
- Parsing PHP string with `$config` variable (#118)

## 2.0.0 - 2018-10-03

### Added
- Usage of short array syntax (#109)
- Support for string parsers (#111)

### Breaking changes
- Changes of interface and parsers

## 1.1.0 - 2018-08-22

### Added
- Added support for PHP constants in YAML (#112)

## 1.0.1 - 2018-03-31

### Fixed
- Possibility to use an own file parser (#103)

## 1.0.0 - 2018-03-03

### Added
- Merge support (#96)
- Set PHP 5.5.9 as minimum required version (#75 and #99)

### Fixed
- Fix PHP 5.6 test (#100)
- Edit PHP versions tested on Travis (#101)
- Add more info about the symfony/yaml requirement (#97 and #102)

### Breaking changes
- PHP 5.3 and 5.4 are no longer supported.

## 0.10.0 - 2016-02-11

### Added
- Package-level exceptions so callers can catch exceptions at package-level
- Added support for files suffixed with the `.dist` extension

### Fixed
- Rearranged error-handling in `FileParser\Json` for better test coverage
- Project-wide code style fixes to adhere to PSR-2
- Fixes `has()` method returning `false` on `null` values in a config field

## 0.9.1 - 2016-01-23

### Added
- PHP 7.0 is now tested on Travis

## 0.9.0 - 2015-10-22

### Added
- Added namespace to example in `README.md`
- Added `has()` method to `ConfigInterface` and implemented in `AbstractConfig`
- Added `all()` method to `ConfigInterface` and implemented in `AbstractConfig`
- Added documentation for new methods
- `AbstractConfig` now implements the `Iterator` interface

### Fixed
- PSR-2 compliance
- Give YamlParser file content instead of path
- Updated `AbstractConfig` constructor to only accept arrays
- Removed check to fix loading an empty array
- Fix for #44: Warnings emitted if configuration file is empty
- Fix for #55: Unset cache after a set


## 0.8.2 - 2015-03-21

### Fixed
- Some code smells in `Config`
- Updated README.md


## 0.8.1 - 2015-03-21

### Fixed
- Various things relating to recent repo transfer


## 0.8.0 - 2015-03-21

### Added
- Individual `FileParser` classes for each filetype, and a `FileParserInterface` to type-hint methods with
- Optional paths; you can now prefix a path with '?' and `Config` will skip the file if it doesn't exist

### Fixed
- Made the Symfony YAML component a suggested dependency
- Parent constructor was not being called from `Config`


## 0.7.1 - 2015-02-24

### Added
- Moved file logic into file-specific loaders

### Fixed
- Corrected class name in README.md


## 0.7.0 - 2015-02-23

### Fixed
- Removed kludgy hack for YAML/YML


## 0.6.0 - 2015-02-23

### Added
- Can now extend `AbstractConfig` to create simple subclasses without any file IO


## 0.5.0 - 2015-02-23

### Added
- Moved file logic into file-specific loaders

### Fixed
- Cleaned up exception class constructors, PSR-2 compliance


## 0.4.0 - 2015-02-22

### Fixed
- Moved file logic into file-specific loaders


## 0.3.0 - 2015-02-22

### Fixed
- Created new classes `ConfigInterface` and `AbstractConfig` to simplify code


## 0.2.1 - 2015-02-22

### Added
- Array and directory support in constructor

### Fixed
- Corrected deprecated usage of `Symfony\Yaml`

## 0.2.0 - 2015-02-21

### Added
- Array and directory support in constructor

### Fixed
- Now can load .YAML and .YML files


## 0.1.0 - 2014-11-27

### Added
- Uses PSR-4 for autoloading
- Supports YAML
- Now uses custom exceptions


## 0.0.1 - 2014-11-19

### Added
- Tagged first release
