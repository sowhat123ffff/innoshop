# Changelog

## [Unreleased]

### Added
- Added support for two additional product sections (4 and 5) on the homepage
- Added `@hookinsert('home.content.bottom4')` and `@hookinsert('home.content.bottom5')` in themes/default/views/home.blade.php
- Added setupProductSection(4) and setupProductSection(5) calls in Boot.php
- Added section 4 and 5 checkboxes in the admin panel product edit page
- Added section 4 and 5 configuration fields in fields.php
- Updated JavaScript code to handle saving and loading section 4 and 5 product assignments

### Changed
- Enhanced product section management to support up to 5 custom product sections

### Fixed
- None

## [1.0.0] - Initial Release
- Base functionality for custom product sections (1-3)
