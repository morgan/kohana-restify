# 0.7.0 - 12/12/2012

- Upgraded to support Kohana 3.3
- Renamed class files and directories to support PSR-0
- Performed basic formatting clean up
- All tests pass: "OK (2 tests, 18 assertions)"

# 0.6.0 - 11/09/2012

- Added config option for specifying body of HTTP request instead of key/value pair. This only 
applies to POST and PUT requests.
- All tests pass: "OK (2 tests, 18 assertions)"

# 0.5.0 - 09/14/2012

- Added support for formatting JSON
- Updated jQuery to 1.8.1
- Updated jQuery UI to 1.8.23
- Tested with Kohana 3.2.2
- All tests pass: "OK (1 test, 12 assertions)"

# 0.4.0 - 09/17/2011

- Resolved defect with deleting data and header rows
- Resolved defect with double sanitizing input data
- Upgraded module to support Kohana 3.2
- Added support for transparent extending of `Restify_Request` and `Restify_Response` classes

# 0.3.0 - 07/22/2011

- Added "Getting Started" dialog with links for a Vimeo demo, GitHub, Twitter and a list of sample URLs
- Various updates to the UI including sticky footer, Facebook like button and getting started action toggle
- Added broad support for SSL requests
- Limiting max redirects to five
- Cleaned up media removing ui-lightness jQuery UI theme

# 0.2.0 - 07/19/2011

- Added `Restify_Response::_parse_cookie` and corresponding `Controller_Restify::_sanitize_cookies`
- Handling new parsed cookie format within UI
- Fixed response output compatibility with Safari
- Removed setting for disabling the return of cookies (now always returning cookies)

# 0.1.0 - 07/17/2011

- Initial release of Restify
- `Restify_Request` and `Restify_Response` for wrapping cURL
- Test controller for reflection
- Unit test coverage
