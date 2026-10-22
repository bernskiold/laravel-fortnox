# Changelog

All notable changes to `laravel-fortnox` will be documented in this file.

## 0.2.0 - 2026-10-22

### Fixed

- The `fortnox` Socialite driver failed to resolve on Laravel 13 with "No Socialite driver was specified.", breaking token refresh and every API call. Laravel 13 rebinds `extend()` callbacks to the manager, so the driver closure no longer relies on `$this`. (#27)

### Changed

- **Dropped Laravel 11 support.** Requires Laravel 12 or 13.
- Added `FortnoxSocialiteProvider::resolve()` as a typed accessor for the registered driver.
