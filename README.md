# Pure Charity Fundraisers Plugin

A Plugin to show fundraisers from the Pure Charity app on your WordPress site.

It depends on the Pure Charity Base Plugin being installed and it's credentials configured to work.

# Installation

IMPORTANT:  At this time the plugin requires a name change after extracting from Github.  After downloading the source code from Github unzip the files and rename the folder **/purecharity-wp-fundraisers** and compress as **purecharity-wp-fundraisers.zip** if you plan to use the Wordpress plugin installer via upload.   

In order to install the plugin:

1. Copy the `/purecharity-wp-fundraisers` folder to the `/wp-content/plugins` on your WP install
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You're done!


## Shortcodes

### Fundraisers Listing
`[fundraisers]`

Possible parameters:
* `grid` - (true|false) Defaults to false. Set to true to enable the alternative grid view
* `title` - (founder_name|title_and_owner_name) What to use on the title of the fundraisers. If not present, uses the fundraiser's title
* `campaign` - Campaign slug to pull fundraisers from
* `partner_slug` - Filter result data by this field.
* `layout` - (1|2|3) Defaults to 1. 1) 4 columns, 2) 3 columns, 3) simplified 3 columns
* `per_page` - The amount of records to fetch per page
* `order` - (title|name|last_name|date) Sort by name, last name or date
* `dir` - (asc|desc) Direction of sorting
* `hide_search` - (true) Option to hide the search in specific shortcode applications
* `layout` - (1|2|3) Choose which layout to use for the shortcode to overwrite the plugin's settings (Only works if grid=true)

### Last Fundraisers Listing
`[last_fundraisers]`

Possible parameters:
* `title` - (founder_name|title_and_owner_name) What to use on the title of the fundraisers. If not present, uses the fundraiser's title
* `limit` - Defaults to 4. Set the number of fundraisers to show
* `order` - (name|date) Sort by name or date
* `dir` - (asc|desc) Direction of sorting

### Single Fundraiser
`[fundraiser slug=fundraiser-slug]`

Possible parameters:
* `slug` - (required) The slug of the fundraiser on the Pure Charity app.
* `title` - (founder_name|title_and_owner_name) What to use on the title of the fundraisers. If not present, uses the fundraiser's title


## Template Tags

### Fundraisers

> Returns a JSON string with the fundraisers requested

Function:
`pc_fundraisers()`

Parameters:

The parameters are passed as an array.

```php
 $options = array();
 $options['param'] = 'value';
```

Possible parameters:
* `limit` - Defaults to 9999. Set the number of fundraisers to show

### Last Fundraisers

> Returns HTML ready for use

Function:
`pc_last_fundraisers()`

Parameters:

The parameters are passed as an array.

```php
 $options = array();
 $options['param'] = 'value';
```

Possible parameters:
* `limit` - Defaults to 4. Set the number of fundraisers to show

### Fundraiser Info

> Returns JSON information for the fundraiser requested

Function:
`pc_fundraiser_info($slug)`

Parameters:

`$slug`: The fundraiser's slug
