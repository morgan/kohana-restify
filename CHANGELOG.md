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