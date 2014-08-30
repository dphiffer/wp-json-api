=== JSON API ===
Contributors: dphiffer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DH4MEG99JR2WE
Tags: json, api, ajax, cms, admin, integration, moma
Requires at least: 2.8
Tested up to: 3.5.2
Stable tag: 1.1.1

A RESTful API for WordPress

== Description ==

JSON API allows you to retrieve and manipulate WordPress content using HTTP requests. There are three main goals:

1. Provide a simple, consistent external interface
2. Create a stable, understandable internal implementation
3. Enable new types of extensions for WordPress

This plugin was created at [The Museum of Modern Art](http://moma.org/) for the weblog [Inside/Out](http://moma.org/explore/inside_out), which is served from Ruby on Rails. Instead of reimplementing the site templates as a WordPress theme, we opted for a Rails front-end that displays content served from a WordPress back-end. JSON API provides the necessary interface for retrieving content and accepting comment submissions.

See the [Other Notes](http://wordpress.org/extend/plugins/json-api/other_notes/) section for the complete documentation.

== Installation ==

1. Upload the `json-api` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.

== Screenshots ==

1. Our old friend, in JSON format

== Documentation ==

1. [General concepts](#1.-General-Concepts)  
   1.1. [Requests](#1.1.-Requests)  
   1.2. [Controllers](#1.2.-Controllers)  
   1.3. [Responses](#1.3.-Responses)  
2. [Request methods](#2.-Request-methods)  
   2.1. [Core controller methods](#2.1.-Core-controller-methods)  
   2.2. [Posts controller methods](#2.2.-Pages-controller-methods)  
   2.3. [Respond controller methods](#2.3.-Respond-controller-methods)  
   2.4. [Widgets controller methods](#2.4.-Widgets-controller-methods)  
3. [Request arguments](#3.-Request-arguments)  
   3.1. [Output-modifying arguments](#3.1.-Output-modifying-arguments)  
   3.2. [Content-modifying arguments](#3.2.-Content-modifying-arguments)  
   3.3. [Using include/exclude and redirects](#3.3.-Using-include/exclude-and-redirects)  
4. [Response objects](#4.-Response-objects)  
   4.1. [Post response object](#4.1.-Post-response-object)  
   4.2. [Category response object](#4.2.-Category-response-object)  
   4.3. [Tag response object](#4.3.-Tag-response-object)  
   4.4. [Author response object](#4.4.-Author-response-object)  
   4.5. [Comment response object](#4.5.-Comment-response-object)  
   4.6. [Attachment response object](#4.6.-Attachment-response-object)  
5. [Extending JSON API](#5.-Extending-JSON-API)  
   5.1. [Plugin hooks](#5.1.-Plugin-hooks)  
   5.2. [Developing JSON API controllers](#5.2.-Developing-JSON-API-controllers)  
   5.3. [Configuration options](#5.3.-Configuration-options)  
6. [Unit tests](#6.-Unit-tests)  
   6.1. [Preparing a WordPress test site](#6.1.-Preparing-a-WordPress-test-site)  
   6.2. [Running the tests](#6.2.-Running-the-tests)  

== 1. General Concepts ==

== 1.1. Requests ==

Requests use a simple REST-style HTTP GET or POST. To invoke the API, include a non-empty query value for `json` in the URL.

JSON API operates in two modes:

1. *Implicit mode* is triggered by setting the `json` query var to a non-empty value on any WordPress page. The content that would normally appear on that page is returned in JSON format.
2. *Explicit mode* is triggered by setting `json` to a known method string. See *Section 2: Request methods* for a complete method listing.

= Implicit mode examples: =

 * `http://www.example.org/?json=1`
 * `http://www.example.org/?p=47&json=1`
 * `http://www.example.org/tag/banana/?json=1`

= Explicit mode examples: =

* `http://www.example.org/?json=get_recent_posts`
* `http://www.example.org/?json=get_post&post_id=47`
* `http://www.example.org/?json=get_tag_posts&tag_slug=banana`

= With user-friendly permalinks configured: =

* `http://www.example.org/api/get_recent_posts/`
* `http://www.example.org/api/get_post/?post_id=47`
* `http://www.example.org/api/get_tag_posts/?tag_slug=banana`

__Further reading__  
See *Section 3: Request arguments* for more information about request arguments to modify the response.

== 1.2. Controllers ==

The 1.0 release of JSON API introduced a modular controller system. This allows developers to flexibly add features to the API and give users more control over which methods they have enabled.

= The Core controller =

Most of the methods available prior to version 1.0 have been moved to the Core controller. The two exceptions are `submit_comment` and `create_post` which are now available from the Respond and Posts controllers, respectively. The Core controller is the only one enabled by default. All other functionality must be enabled from the JSON API Settings page (under Settings in the WordPress admin menu).

= Specifying a controller =

There are a few ways of specifying a controller, depending on how you are calling the API:

* `http://www.example.org/?json=get_recent_posts` (`core` controller is implied, method is `get_recent_posts`)
* `http://www.example.org/api/info/` (`core` controller is implied)
* `http://www.example.org/api/core/get_category_posts/` (`core` controller can also be explicitly specified)
* `http://www.example.org/?json=respond.submit_comment` (`respond` controller, `submit_comment` method)

__Legacy compatibility__  
JSON API retains support for its pre-1.0 methods. For example, if you invoke the method `create_post` without a controller specified, the Posts controller is chosen instead of Core.

= Available controllers =

The current release includes three controllers: Core, Posts, and Respond. Developers are encouraged to suggest or submit additional controllers.

__Further reading__  
See *Section 2: Request methods* for a complete reference of available controllers and methods. For documentation on extending JSON API with new controllers see *Section 5.2: Developing JSON API controllers*.

== 1.3. Responses ==

The standard response format for JSON API is (as you may have guessed) [JSON](http://json.org/).

Here is an example response from `http://localhost/wordpress/?json=1` called on a default WordPress installation (formatted for readability):

    {
      "status": "ok",
      "count": 1,
      "count_total": 1,
      "pages": 1,
      "posts": [
        {
          "id": 1,
          "type": "post",
          "slug": "hello-world",
          "url": "http:\/\/localhost\/wordpress\/?p=1",
          "title": "Hello world!",
          "title_plain": "Hello world!",
          "content": "<p>Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!<\/p>\n",
          "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!\n",
          "date": "2009-11-11 12:50:19",
          "modified": "2009-11-11 12:50:19",
          "categories": [],
          "tags": [],
          "author": {
            "id": 1,
            "slug": "admin",
            "name": "admin",
            "first_name": "",
            "last_name": "",
            "nickname": "",
            "url": "",
            "description": ""
          },
          "comments": [
            {
              "id": 1,
              "name": "Mr WordPress",
              "url": "http:\/\/wordpress.org\/",
              "date": "2009-11-11 12:50:19",
              "content": "<p>Hi, this is a comment.<br \/>To delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.<\/p>\n",
              "parent": 0
            }
          ],
          "comment_count": 1,
          "comment_status": "open"
        }
      ]
    }

== 2. Request methods ==

Request methods are available from the following controllers:

* Core controller - basic introspection methods
* Posts controller - data manipulation methods for posts
* Respond controller - comment/trackback submission methods
* Widgets controller - retrieve sidebar widgets

== 2.1. Core controller methods ==

The Core controller offers a mostly-complete set of introspection methods for retrieving content from WordPress.


== Method: info ==

Returns information about JSON API.

= Optional arguments =

* `controller` - returns detailed information about a specific controller

= Response =

    {
      "status": "ok",
      "json_api_version": "1.0",
      "controllers": [
        "core"
      ]
    }


= Response with “controller=core” =

    {
      "status": "ok",
      "name": "Core",
      "description": "Basic introspection methods",
      "methods": [
        ...
      ]
    }
    

== Method: get_recent_posts ==

Returns an array of recent posts. You can invoke this from the WordPress home page either by setting `json` to a non-empty value (i.e., `json=1`) or from any page by setting `json=get_recent_posts`.

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }
    

== Method: get_posts ==

Returns posts according to WordPress's [`WP_Query` parameters](http://codex.wordpress.org/Class_Reference/WP_Query#Parameters). The one default parameter is `ignore_sticky_posts=1` (this can be overridden).

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

__Further reading__  
See the [`WP_Query` documentation](http://codex.wordpress.org/Class_Reference/WP_Query#Parameters) for a full list of supported parameters. The `post_status` parameter is currently ignored.

= Response =

    {
      "status": "ok",
      "count": 1,
      "posts": [
        { ... }
      ]
    }
    

== Method: get_post ==

Returns a single post object.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a post URL
* `id` or `post_id` - set to the post's ID
* `slug` or `post_slug` - set to the post's URL slug

= Optional arguments =

* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "post": { ... }
    }


== Method: get_page ==

Returns a single page object.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a page URL
* `id` or `page_id` - set to the page's ID
* `slug` or `page_slug` - set to the page's URL slug

= Optional arguments =

* `children` - set to a non-empty value to include a recursive hierarchy of child pages
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "page": { ... }
    }

== Method: get_date_posts ==

Returns an array of posts/pages in a specific date archive (by day, month, or year).

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a date archive page
* `date` - set to a date in the format `YYYY` or `YYYY-MM` or `YYYY-MM-DD` (non-numeric characters are stripped from the var, so `YYYYMMDD` or `YYYY/MM/DD` are also valid)

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }

== Method: get_category_posts ==

Returns an array of posts/pages in a specific category.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a category archive page
* `id` or `category_id` - set to the category's ID
* `slug` or `category_slug` - set to the category's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "category": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_tag_posts ==

Returns an array of posts/pages with a specific tag.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a tag archive page
* `id` or `tag_id` - set to the tag's ID
* `slug` or `tag_slug` - set to the tag's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "tag": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_author_posts ==

Returns an array of posts/pages written by a specific author.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on an author archive page
* `id` or `author_id` - set to the author's ID
* `slug` or `author_slug` - set to the author's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "author": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_search_results ==

Returns an array of posts/pages in response to a search query.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a search results page
* `search` - set to the desired search query

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_date_index ==

Returns both an array of date page permalinks and a tree structure representation of the archive.

= Response =

    {
      "status": "ok",
      "permalinks": [
        "...",
        "...",
        "..."
      ],
      "tree": {
        "2009": {
          "09": 17,
          "10": 20,
          "11": 7
        }
      }

Note: the tree is arranged by `response.tree.[year].[month].[number of posts]`.


== Method: get_category_index ==

Returns an array of active categories.

= Optional argument =

* `parent` - returns categories that are direct children of the parent ID

= Response =

    {
      "status": "ok",
      "count": 3,
      "categories": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_tag_index ==

Returns an array of active tags.

= Response =

    {
      "status": "ok",
      "count": 3
      "tags": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_author_index ==

Returns an array of active blog authors.

= Response =

    {
      "status": "ok",
      "count": 3,
      "authors": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_page_index ==

Returns a hierarchical tree of `page` posts.

= Response =

    {
      "status": "ok",
      "pages": [
        { ... },
        { ... },
        { ... }
      ]
    }

== Method: get_nonce ==

Returns a WordPress nonce value, required to call some data manipulation methods.

= Required arguments =

* `controller` - the JSON API controller for the method you will use the nonce for
* `method` - the method you wish to call (currently `create_post` is the only method that requires a nonce)

= Response =

    {
      "status": "ok",
      "controller": "posts",
      "method": "create_post",
      "nonce": "cefe01efd4"
    }

__Further reading__  
To learn more about how nonces are used in WordPress, see [Mark Jaquith's article on the subject](http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/).

== 2.2. Pages controller methods ==

== Method: create_post ==

Creates a new post.

= Required argument =

* `nonce` - available from the `get_nonce` method (call with vars `controller=posts` and `method=create_post`)

= Optional arguments =

* `status` - sets the post status ("draft" or "publish"), default is "draft"
* `title` - the post title
* `content` - the post content
* `author` - the post's author (login name), default is the current logged in user
* `categories` - a comma-separated list of categories (URL slugs)
* `tags` - a comma-separated list of tags (URL slugs)

Note: including a file upload field called `attachment` will cause an attachment to be stored with your new post.

== Method: update_post ==

Updates a post.

= Required argument =

* `nonce` - available from the `get_nonce` method (call with vars `controller=posts` and `method=update_post`)

= One of the following is required =

* `id` or `post_id` - set to the post's ID
* `slug` or `post_slug` - set to the post's URL slug

= Optional arguments =

* `status` - sets the post status ("draft" or "publish"), default is "draft"
* `title` - the post title
* `content` - the post content
* `author` - the post's author (login name), default is the current logged in user
* `categories` - a comma-separated list of categories (URL slugs)
* `tags` - a comma-separated list of tags (URL slugs)

Note: including a file upload field called `attachment` will cause an attachment to be stored with your post.

== Method: delete_post ==

Deletes a post.

= Required argument =

* `nonce` - available from the `get_nonce` method (call with vars `controller=posts` and `method=delete_post`)

= One of the following is required =

* `id` or `post_id` - set to the post's ID
* `slug` or `post_slug` - set to the post's URL slug


== 2.3. Respond controller methods ==

== Method: submit_comment ==

Submits a comment to a WordPress post.

= Required arguments =

* `post_id` - which post to comment on
* `name` - the commenter's name
* `email` - the commenter's email address
* `content` - the comment content

= Optional arguments =

* `redirect` - redirect instead of returning a JSON object
* `redirect_ok` - redirect to a specific URL when the status value is `ok`
* `redirect_error` - redirect to a specific URL when the status value is `error`
* `redirect_pending` - redirect to a specific URL when the status value is `pending`

= Custom status values =

* `pending` - assigned if the comment submission is pending moderation

== 2.4. Widgets controller methods ==

== Method: get_sidebar ==

Retrieves widgets assigned to a sidebar.

= Required arguments =

* `sidebar_id` - the name or number of the sidebar to retrieve


== 3. Request arguments ==

API requests can be controlled by specifying one of the following arguments as URL query vars.

= Examples =

* Debug the response: `http://www.example.org/api/get_page_index/?dev=1`
* Widget-style JSONP output: `http://www.example.org/api/get_recent_posts/?callback=show_posts_widget&read_more=More&count=3`
* Redirect on error: `http://www.example.org/api/posts/create_post/?callback_error=http%3A%2F%2Fwww.example.org%2Fhelp.html`

== 3.1. Output-modifying arguments ==

The following arguments modify how you get results back from the API. The redirect response styles are intended for use with the data manipulation methods.

* Setting `callback` to a JavaScript function name will trigger a JSONP-style callback.
* Setting `redirect` to a URL will cause the user's browser to redirect to the specified URL with a `status` value appended to the query vars (see the *Response objects* section below for an explanation of status values).
* Setting `redirect_[status]` allows you to control the resulting browser redirection depending on the `status` value.
* Setting `dev` to a non-empty value adds whitespace for readability and responds with `text/plain`
* Errors are suppressed unless `dev` is set to a non-empty value
* Setting `json_encode_options` will let you specify an integer bitmask to modify the behavior of [PHP's `json_encode`](http://php.net/manual/en/function.json-encode.php) (Note: this option is only recognized in PHP version 5.3+)
* Setting `json_unescaped_unicode` will replace unicode-escaped characters with their unescaped equivalents (e.g., `\u00e1` becomes á)
* Omitting all of the above arguments will result in a standard JSON response.

== 3.2. Content-modifying arguments ==

These arguments are available to modify all introspection methods:

* `date_format` - Changes the format of date values. Uses the same syntax as PHP's date() function. Default value is `Y-m-d H:i:s`.
* `read_more` - Changes the 'read more' link text in post content.
* `include` - Specifies which post data fields to include. Expects a comma-separated list of post fields. Leaving this empty includes *all* fields.
* `exclude` - Specifies which post data fields to exclude. Expects a comma-separated list of post fields.
* `custom_fields` - Includes values from posts' Custom Fields. Expects a comma-separated list of custom field keys.
* `author_meta` - Includes additional author metadata. Should be a comma-separated list of metadata fields.
* `count` - Controls the number of posts to include (defaults to the number specified by WordPress)
* `order` - Controls the order of post results ('DESC' or 'ASC'). Default value is 'DESC'.
* `order_by` - Controls which field to order results by. Expects one of the following values:
  * `author`
  * `date` (default value)
  * `title`
  * `modified`
  * `menu_order` (only works with Pages)
  * `parent`
  * `ID`
  * `rand`
  * `meta_value` (`meta_key` must also be set)
  * `none`
  * `comment_count`
* `meta_key`, `meta_value`, `meta_compare` - Retrieve posts (or Pages) based on a custom field key or value.

== 3.3. Using include/exclude and redirects ==

__About `include`/`exclude` arguments__  
By default you get all values included with each post object. Specify a list of `include` values will cause the post object to filter out the values absent from the list. Specifying `exclude` causes post objects to include all values except the fields you list. For example, the query `exclude=comments` includes everything *except* the comments.

__About the `redirect` argument__  
The `redirect` response style is useful for when you need the user's browser to make a request directly rather than making proxy requests using a tool like cURL. Setting a `redirect` argument causes the user's browser to redirect back to the specified URL instead of returning a JSON object. The resulting `status` value is included as an extra query variable.

For example calling an API method with `redirect` set to `http://www.example.com/foo` will result in a redirection to one of the following:

* `http://www.example.com/foo?status=ok`
* `http://www.example.com/foo?status=error`

You can also set separate URLs to handle status values differently. You could set `redirect_ok` to `http://www.example.com/handle_ok` and `redirect_error` to `http://www.example.com/handle_error` in order to have more fine-tuned control over the method result.


== 4. Response objects ==

This section describes data objects you can retrieve from WordPress and the optional URL redirects.

__Status values__  
All JSON API requests result in a status value. The two basic status values are `ok` and `error`. Additional status values are available for certain methods (such as `pending` in the case of the `submit_comment` method). API methods that result in custom status values include a *custom status values* section in their documentation.

__Naming compatibility__  
Developers familiar with WordPress may notice that many names for properties and arguments have been changed. This was a stylistic choice that intends to provide more clarity and consistency in the interface.

== 4.1. Post response object ==

* `id` - Integer
* `type` - String (e.g., `post` or `page`)
* `slug` - String
* `url` - String
* `title` - String
* `title_plain` - String
* `content` - String (modified by the `read_more` argument)
* `excerpt` - String
* `date` - String (modified by the `date_format` argument)
* `modified` - String (modified by the `date_format` argument)
* `categories` - Array of category objects
* `tags` - Array of tag objects
* `author` Author object
* `comments` - Array of comment objects
* `attachments` - Array of attachment objects
* `comment_count` - Integer
* `comment_status` - String (`"open"` or `"closed"`)
* `thumbnail` - String (only included if a post thumbnail has been specified)
* `custom_fields` - Object (included by setting the `custom_fields` argument to a comma-separated list of custom field names)
* `taxonomy_(taxonomy)` - Array of custom taxonomy objects (these resemble Category or Tag response objects, depending on whether the taxonomy is hierarchical)

__Note__  
The `thumbnail` attribute returns a URL to the image size specified by the optional `thumbnail_size` request argument. By default this will use the `thumbnail` or `post-thumbnail` sizes, depending on your version of WordPress. See [Mark Jaquith's post on the topic](http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/) for more information.

== 4.2. Category response object ==

* `id` - Integer
* `slug` - String
* `title` - String
* `description` - String
* `parent` - Integer
* `post_count` - Integer

== 4.3. Tag response object ==

* `id` - Integer
* `slug` - String
* `title` - String
* `description` - String
* `post_count` - Integer

== 4.4. Author response object ==

* `id` - Integer
* `slug` - String
* `name` - String
* `first_name` - String
* `last_name` - String
* `nickname` - String
* `url` - String
* `description` - String
  
Note: You can include additional values by setting the `author_meta` argument to a comma-separated list of metadata fields.

== 4.5. Comment response object ==

* `id` - Integer
* `name` - String
* `url` - String
* `date` - String
* `content` - String
* `parent` - Integer
* `author` - Object (only set if the comment author was registered & logged in)

== 4.6. Attachment response object ==

* `id` - Integer
* `url` - String
* `slug` - String
* `title` - String
* `description` - String
* `caption` - String
* `parent` - Integer
* `mime_type` - String
* `images` - Object with values including `thumbnail`, `medium`, `large`, `full`, each of which are objects with values `url`, `width` and `height` (only set if the attachment is an image)


== 5. Extending JSON API ==

JSON API exposes several WordPress action and filter hooks as well as a modular controller system for adding new API methods.

== 5.1. Plugin hooks ==

JSON API exposes several [action and filter hooks](http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters) to augment its behavior.

== Filter: json_api_controllers ==

This filter controls the array of controllers available to JSON API. The callback function is passed a single argument, an array of strings.

= Example =
    
    // Add a custom controller
    add_filter('json_api_controllers', 'add_my_controller');
    
    function add_my_controller($controllers) {
      // Corresponds to the class JSON_API_MyController_Controller
      $controllers[] = 'MyController';
      return $controllers;
    }


== Filter: json_api_[controller]_controller_path ==

Specifies the PHP source file for a given controller, overriding the default location `wp-content/plugins/json_api/controllers`.

__Note__  
If you your controller file in the `json-api/controllers` folder JSON API will find it automatically.

= Example =

    // Register the source file for JSON_API_Widgets_Controller
    add_filter('json_api_widgets_controller_path', 'widgets_controller_path');
    
    function widgets_controller_path($default_path) {
      return '/path/to/widgets.php';
    }

__Capitalization__  
Your filter hook must be all-lowercase to work correctly. The above example would fail with the filter `json_api_Widgets_Controller_path`, even if that's how the class is capitalized in the PHP source.

== Filter: json_api_encode ==

This is called just before the output is encoded into JSON format. The value passed will always be an associative array, according to the format described in each method's documentation. Those items described in the *Response objects* section are passed as PHP objects, not associative arrays.

= Example =

    add_filter('json_api_encode', 'my_encode_kittens');
    
    function my_encode_kittens($response) {
      if (isset($response['posts'])) {
        foreach ($response['posts'] as $post) {
          my_add_kittens($post); // Add kittens to each post
        }
      } else if (isset($response['post'])) {
        my_add_kittens($response['post']); // Add a kittens property
      }
      return $response;
    }
    
    function my_add_kittens(&$post) {
      $post->kittens = 'Kittens!';
    }

== Action: json_api-[controller]-[method] ==

Each JSON API method invokes an action when called.

= Example =
    
    // Disable get_author_index method (e.g., for security reasons)
    add_action('json_api-core-get_author_index', 'my_disable_author_index');
    
    function my_disable_author_index() {
      // Stop execution
      exit;
    }

== 5.2. Developing JSON API controllers ==

= Creating a controller =

To start a new JSON API controller, create a file called `hello.php` inside `wp-content/plugins/json-api/controllers`. Add the following class definition:

    <?php
    
    class JSON_API_Hello_Controller {
      
      public function hello_world() {
        return array(
          "message" => "Hello, world"
        );
      }
      
    }
    
    ?>
    
Your controller is now available as `hello`, and exposes one `hello_world` method.
    
Next, activate your controller from the WordPress admin interface, available from the menu under Settings > JSON API. You can either click on the link to your `hello_world` method from the admin interface or enter it manually. It should have the form: `http://www.example.org/api/hello/hello_world/?dev=1` or `http://www.example.org/?json=hello.hello_world&dev=1` (note the use of the `dev` argument to enable human-readable output). You should get the following output:

    {
      "status": "ok",
      "message": "Hello, world"
    }

= Using query vars =

To customize the behavior of your controller, you will want to make use of the global `$json_api->query` object. Add the following method to your controller:

    public function hello_person() {
      global $json_api;
      $name = $json_api->query->name;
      return array(
        "message" => "Hello, $name."
      );
    }

Now append the `name` query var to the method call: `http://www.example.org/api/hello/hello_world/?dev=1&name=Alice` or `http://www.example.org/?json=hello.hello_world&dev=1&name=Alice`.

    {
      "status": "ok",
      "message": "Hello, Alice"
    }

= Introspector and data models =

Your controller can use any of the [existing WordPress functions](http://codex.wordpress.org/Function_Reference) to collect data, but JSON API also includes an introspector that wraps data in objects defined in the `json-api/models` directory. These are the same data models described in *Section 4: Response objects*.

Here is an example of how you might use the introspector:

    // Retrieve posts based on custom field key/value pair
    public function get_custom_posts() {
      global $json_api;
      
      // Make sure we have key/value query vars
      if (!$json_api->query->key || !$json_api->query->value) {
        $json_api->error("Include a 'key' and 'value' query var.");
      }
      
      // See also: http://codex.wordpress.org/Template_Tags/query_posts
      $posts = $json_api->introspector->get_posts(array(
        'meta_key' => $json_api->query->key,
        'meta_value' => $json_api->query->value
      ));
      
      return array(
        'key' => $key,
        'value' => $value,
        'posts' => $posts
      );
    }

= External controllers =

It is recommended that custom controllers are kept outside of `json-api/controllers` in order to avoid accidental deletion during upgrades or site migrations. To make your controller visible from an external plugin or theme directory you will need to use two filters: `json_api_controllers` and `json_api_[controller]_controller_path`. Move the `hello.php` file from the steps above into your theme's directory. Then add the following to your theme's `functions.php` file (if your theme doesn't have a file called `functions.php` you can create one).

    function add_hello_controller($controllers) {
      $controllers[] = 'hello';
      return $controllers;
    }
    add_filter('json_api_controllers', 'add_hello_controller');
    
    function set_hello_controller_path() {
      return "/path/to/theme/hello.php";
    }
    add_filter('json_api_hello_controller_path', 'set_hello_controller_path');

== 5.3. Configuration options ==

The following are constants you can define in your `wp-config.php` folder:

* `JSON_API_DIR` - set to the directory where JSON API plugin lives (in some cases this can be useful for `mu-plugins` with WordPress MU)
* `JSON_API_CONTROLLERS` - a comma-separated list of default controllers to enable (this is overridden by the JSON API settings page)

== 6. Unit tests ==

JSON API comes with a set of tests that should make it easier to maintain and reveal incompatibilities when they might occur. This is an ongoing process, I hope to improve the test coverage going forward.

== 6.1. Preparing a WordPress test site ==

There are a few necessary steps that need to be carried out before the test suite will run properly.

1. WordPress should generate a new set of tables before you start, so if you're testing with a `wp_` table prefix make sure the database has no existing tables of this kind
2. Configure and install a new copy of WordPress
3. Delete the Hello World post and Sample Page (titled "About" in some versions of WordPress)
4. Enable user-friendly URLs from Settings > Permalinks, use the "Day and name" format
5. Install + Activate the JSON API plugin and enable all bundled controllers from Settings > JSON API
6. Import the [Theme Unit Test](http://codex.wordpress.org/Theme_Unit_Test) test data XML file from Settings > Import > WordPress (you will need to install the WordPress Importer plugin)

== 6.2. Running the tests ==

From the command line, make sure you have the HTTP_Client PEAR package installed:

`pear install HTTP_Client`

Change directory to `tests` and run the following:

`pear run-tests`

You should see the test results print out culminating in a summary:

    TOTAL TIME: 00:04
    23 PASSED TESTS
    0 SKIPPED TESTS

== Changelog ==

= 1.1.1 (2013-06-23): =
* Added support for custom taxonomies
* Errors are now suppressed unless you include a non-empty `dev` argument

= 1.1.0 (2013-06-22): =
* Bugfix for `json_encode` compatibility with PHP < 5.3
* Bugfix for `get_author_index` warnings in WordPress > 3.5

= 1.0.9 (2013-06-21): =
* Added `update_post` and `delete_post` methods to Post controller
* Added two JSON encoding arguments: `json_encode_options` and `json_unescaped_unicode`
* Added a `parent` argument to `get_category_index`
* Fixed a couple places where the code was generating PHP notifications
* Updated bundled Services_JSON library (only used if `json_encode` is unavailable)

= 1.0.8 (2013-06-12): =
* Added `widgets` controller
* Added a generic `get_posts` method to the core controller
* Added a `thumbnail_images` object property to complement `thumbnail` URL
* Attachment image files are now checked to exist and match the expected width/height
* Fixed a bug where `the_excerpt` filter wasn't being applied to the `excerpt` property
* Fixed a bug where the number of child pages was being limited to 5
* Fixed a bug where custom controller class names couldn't include numerics
* Theme directory check for custom controllers

= 1.0.7 (2011-01-27): =
* Created some basic unit tests
* Fixed a bug where `get_author_posts` was unable to find users by `slug`
* Added missing `post_type` argument to documentation for `get_post` and `get_page` (props Koshirosan)
* Added `previous_url` and `next_url` properties to the `get_post` response object (props mlcy44)

= 1.0.6 (2011-01-13): =
* Fixed a bug in `exclude` query parameter (big props to ikesyo and archon810)
* Fix for `get_page_index` that where it only returned 5 pages -- it now responds to `count` query param (props to npavkovic and blinder)
* Removed `Content-Disposition` header from response (props mimecine, kjwierenga)
* Fixed an incompatibility issue with Disqus plugin (props joshcanhelp)
* Fixed a bug where `submit_comment` was resulting in a HTTP 404 status (props @tdweston)
* Fixed an error in the documentation, external controller example (props jli)

= 1.0.5 (2010-07-08): =
* Added an check so that `json-api.php` can be moved one level above the `json-api` directory
* Added more documentation about using nonces

= 1.0.4 (2010-07-07): =
* Fixed a bug where the order of attachments didn't match the gallery
* Added a section to the developer documentation for externalizing custom controllers
* Moved JSON_API class to its own file: `singletons/api.php`
* Created a new top-level function: `json_api_dir()`
* Improvements for WordPress MU: `JSON_API_DIR` and `JSON_API_CONTROLLERS` constants (props Jim McQuillan)

= 1.0.3 (2010-07-07): =
* Added request argument `thumbnail_size` to support different sizes of featured images (see also: `add_image_size` WordPress function)
* Added request argument `post_type` to support custom post types (props Mark Harris)

= 1.0.2 (2010-07-02): =
* Removed an inaccurate section from readme.txt about supporting `query_posts` arguments
* Changed controller info block format to use "Controller name" and "Controller description"
* Made admin page more robust about handling errors loading controllers
* Changed `JSON_API::get_controllers` method to lowercase all entries
* Added introspector section to developer documentation
* Fixed incorrect example for `json_api_[controller]_controller_path`
* Thanks to Tim Nash for early feedback on writing external controllers

= 1.0.1 (2010-07-01): =
* Fixed some typos in readme.txt
* Switched `get_tag_posts` to query on tag instead of tag_id (maybe a WordPress issue?)

= 1.0 (2010-06-29): =
* JSON API officially drops support for PHP 4 (it was already broken)
* Added JSON API Settings page to WP admin
* Broke apart `JSON_API_Controller` into a modular controller system
* Refactored `JSON_API_Query` to depend less on WordPress's `get_query_var` mechanism
* Developer mode now shows response in JSON format
* The `create_post` method now requires a nonce
* Improved support for complex post queries (props zibitt)
* Fixed a bug with `get_author_by_login` (props Krzysztof Sobolewski)
* Made image attachments more robust with `get_intermediate_image_sizes` (props mimecine)
* Improved post thumbnail support (props nyamsprod)

= 0.9.6 (2010-05-27): =
* Fixed a bug introduced in 0.9.5

= 0.9.5 (2010-05-27): =
* Added a `thumbnail` property to Post response objects

= 0.9.4 (2010-04-28): =
* Fixed a bug where any non-authenticated user could create a draft blog post through `create_post`. Thanks to user futtta for posting about this.

= 0.9.3 (2010-03-19): =
* Fixed a bug where child pages were being ignored by the API. See also: https://core.trac.wordpress.org/ticket/12647

= 0.9.2 (2010-03-18): =
* Fixed a bug where the /api/ rewrite rules would be lost

= 0.9 (2010-02-04): =
* Added a `create_post` method

= 0.8.3 (2010-01-27): =
* Fixed the stable tag version

= 0.8.2 (2010-01-27): =
* Fixed a typo in the changelog

= 0.8.1 (2010-01-27): =
* Fixed a bug that was making JSONP support non-functional

= 0.8 (2010-01-18): =
* Added an attachment model and instance variable for post objects

= 0.7.3 (2010-01-15): =
* Added a `count` request parameter to control the number of posts returned

= 0.7.2 (2010-01-14): =
* Removed the version number from the description text

= 0.7.1 (2010-01-14): =
* Fixed another subtle bug with `get_author_index`

= 0.7 (2010-01-08): =
* Added a `post_count` response to tag objects
* Fixed a bug with `get_author_index`

= 0.6 (2009-11-30): =
* Added `count_total` response
* Added `json_api_encode` filter
* Fixed bugs in the introspector's `get_current_category` and `get_current_tag`

= 0.5 (2009-11-17): =
* Initial Public Release

== Upgrade Notice ==

= 1.1.1 =
Added support for custom taxonomies

= 1.1.0 =
Minor bugfixes

= 1.0.9 =
Update/delete post methods and some other bugfixes and improvements

= 1.0.8 =
Long overdue bugfix/improvement release

= 1.0.7 =
Minor bugfix/improvement release

= 1.0.6 =
Minor bugfix/improvement release

= 1.0.5 =
Minor improvement release

= 1.0.4 =
Minor bugfix/refactor release

= 1.0.3 =
Two new request arguments added: `thumbnail_size` and `post_type`

= 1.0.2 =
Minor bugfix release

= 1.0.1 =
Bugfix release, possibly stemming from a bug in WordPress 3.0

= 1.0 =
Major release, see changelog for details.

= 0.9.6 =
Bugfix release for something added in 0.9.5.

= 0.9.5 =
Feature addition: post thumbnails now included in response objects.

= 0.9.4 =
Security fix: all users are strongly encouraged to upgrade. (See Changelog.)

= 0.9.3 =
Fixed a bug where child pages could not be introspected by the API.

= 0.9.2 =
Fixed a bug where the /api/ path would stop working upon publishing new pages.

= 0.9 =
Added a new data manipulation method: `create_post`.

= 0.8.3 =
Oh dear, I didn't tag 0.8.2 in the stable tags thing.

= 0.8.2 =
Just fixing a mislabeled 0.8.1 release in the changelog.

= 0.8.1 =
This is a bug fix release for JSONP support. Thanks to Ben Wilson for reporting it!

= 0.8 =
Added what may be the last introspection feature: post attachments. You can now see images and other media that have been added to posts.
