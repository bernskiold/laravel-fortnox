# Changelog

All notable changes to `laravel-fortnox` will be documented in this file.

## 0.2.1 - 2026-11-20

### What's Changed

* Add PHP 8.5 and 8.6 support by @ErikBernskiold in https://github.com/bernskiold/laravel-fortnox/pull/28
* Support stable PHP 8.6 by @ErikBernskiold in https://github.com/bernskiold/laravel-fortnox/pull/29

**Full Changelog**: https://github.com/bernskiold/laravel-fortnox/compare/0.2.0...0.2.1

## 0.2.0 - 2026-10-22

### Fixed

- The `fortnox` Socialite driver failed to resolve on Laravel 13 with "No Socialite driver was specified.", breaking token refresh and every API call. Laravel 13 rebinds `extend()` callbacks to the manager, so the driver closure no longer relies on `$this`. (#27)

### Changed

- **Dropped Laravel 11 support.** Requires Laravel 12 or 13.
- Added `FortnoxSocialiteProvider::resolve()` as a typed accessor for the registered driver.
