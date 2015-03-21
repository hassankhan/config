# Changelog

All notable changes to `Config` will be documented in this file

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
