# Pure Charity Fundraisers Plugin

A Plugin to show fundraisers from the Pure Charity app on your WordPress site.

It depends on the Pure Charity Base Plugin being installed and it's credentials configured to work.

# Installation

In order to install the plugin:

1. Copy the `/purecharity-wp-fundraisers` folder to the `/wp-content/plugins` on your WP install
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You're done!

## Template Tags

### Last Fundraisers

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

## Shortcodes

### Fundraisers Listing
`[fundraisers]`

Possible parameters:
* `grid` - (true|false) Defaults to false. Set to true to enable the alternative grid view
* `layout` - (1|2|3) Defaults to 1. 1) 4 columns, 2) 3 columns, 3) simplified 3 columns
* `per_page` - The amount of records to fetch per page
* `title` - (founder_name|title_and_owner_name)What to use on the title of the fundraisers. If not present, uses the fundraiser's title
* `order` - (title|name|last_name|date) Sort by name, last name or date
* `dir` - (asc|desc) Direction of sorting
* `hide_search` - (true) Option to hide the search in specific shortcode applications

### Last Fundraisers Listing
`[last_fundraisers]`

Possible parameters:
* `limit` - Defaults to 4. Set the number of fundraisers to show
* `order` - (name|date) Sort by name or date
* `dir` - (asc|desc) Direction of sorting

### Single Fundraiser
`[fundraiser slug=fundraiser-slug]`

Possible parameters:
* `slug` - The slug of the fundraiser on the Pure Charity app.


