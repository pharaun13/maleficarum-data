# Change Log
This is the Maleficarum Data component. It carries data structures used by maleficarum projects. This only defines generic structures - no storage dependencies.

## [2.0.0] - 2017-08-01
### Changed
- Make use of nullable types provided in PHP 7.1 (http://php.net/manual/en/migration71.new-features.php)

## [1.0.1] - 2017-03-20
### Changed
- Fixed a bug that caused model meta not to be properly merged when the merge method was called.

### Added
- Replaced direct _meta calls in AbstractModel with a call to getMeta() getter method.

## [1.0.0] - 2017-03-20
### Added
- This is an initial release of the component - based on the code written by me and included inside the maleficarum API repository.
