# Changelog

All notable changes to `view-models` will be documented in this file

## 0.1.0 - 2020-08-19
- initial release


## 0.1.1 - 2020-08-19
- fix composer requirements


## 0.1.2 - 2020-08-20
- add use of redisRemember helper function


## 0.1.3 - 2020-08-20
- add use of RedisCache service


## 0.2.0 - 2020-09-14
- add support for Laravel 8


## 0.3.0 - 2020-10-07
- add support for php 7.1 & Laravel 5


## 0.3.1 - 2020-10-07
- bump sfneal/string-helpers min version to prevent conflicts with sfneal/array-helpers


## 0.3.2 - 2020-12-04
- optimize Travis CI test suite
- add support for php8


## 0.3.3 - 2020-12-11
- fix support for php8


## 0.3.4 - 2020-12-14
- add improved type hinting


## 0.3.5 - 2020-12-15
- fix issue with 'none' return type in setDay method()


## 0.3.6 - 2021-01-18
- add setTTL() method to AbstractViewModel for setting $ttl property during runtime


## 0.3.7 - 2021-01-21
- fix use of inString helper function with import of StringHelpers


## 0.4.0 - 2021-01-26
- bump min sfneal/redis-helpers & sfneal/string-helpers composer version to 1.0
- add badges to readme


## 0.4.1 - 2021-01-27
- refactor test suite (brought in several from spatie/laravel-view-models)
- add use of redis-helpers 'ttl' config key instead of env() helper method


## 0.4.2 - 2021-01-27
- fix min spatie/laravel-view-models composer package version


## 1.0.0 - 2021-01-27
- initial production release
- update documentation


## 1.1.0 - 2021-02-02
- add improved test suite to that tests caching
- refactor several private AbstractViewModel methods to public facing methods
- bump min sfneal/redis-helpers version
- fix issue with cache invalidation leading to rendered ViewModels not being deleted


## 1.1.1 - 2021-02-04
- fix AbstractViewModel::$view property type hinting


## 1.1.2 - 2021-02-10
- add sfneal/caching composer requirement to make use of IsCacheable trait
- add use of IsCacheable trait in AbstractViewModel


## 1.1.3 - 2021-02-22
- make PdfViewModel trait for use in ViewModel's that can be used for PDF Exports


## 2.0.0 - 2021-03-29
- make PreCacheViewModel Job for caching ViewModels within the Job queue to provide faster response times
- add sfneal/queueables to composer requirements
- depreciated sfneal/view-model-precache package


## 2.0.1 - 2021-04-05
- fix sfneal/queueables version constraint (^1.0)


## 2.1.0 - 2021-04-05
- bump sfneal/queueables min version to ^2.0


## 3.0.0 - 2021-04-06
- refactor `AbstractViewModel` to `ViewModel`

 
## 3.0.1 - 2021-09-01
- optimize Travis CI config & add code coverage uploading
- add support for sfneal/caching v2.0


## 3.0.2 - 2021-09-01
- add support for sfneal/redis-helpers v2.0
- cut use of `RedisCache::remember()` method


## 3.1.0 - 2021-09-24
- make `CachingPreferences` trait that's used by `ViewModel` for conditionally disabling caching in different app environments
- add `dontCacheInDevelopment()`, `dontCacheInProduction()` & `dontCacheIf()` methods to `CachingPreferences` & `ViewModel`
- add sfneal/laravel-helpers composer requirement for checking app env


## 4.0.0 - 2022-03-01
- cut support for PHP7.2 & below
- add use of GitHub actions
- add support for sfneal/caching version v3.0


## 5.0.0 - 2024-04-17
- cut support for PHP 7
- add use of new GitHub actions
- add support for PHP 8.2 & 8.3


## 5.1.0 - 2024-04-17
- removed unused classes/files
- add return type hinting
- general code cleanup


## 5.1.1 - 2024-04-24
- bump sfneal/caching requirement from ^2.0|^3.0 to ^2.0|^3.0|^4.0


## 5.1.2 - 2024-04-30
- bump sfneal/redis-helpers requirement from ^1.2|^2.0 to ^1.2|^2.0|^3.0
