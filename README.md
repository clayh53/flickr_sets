# Flickr_sets Statamic add-on

## Overview

This Statamic add-on was created as an extension of the great Statamic *flickr* plugin created by Nikolai Strandskogen [here](https://github.com/laien/Statamic-Flickr-Plugin). I have slightly modified the plugin portion of to make use of site-wide global variables fetched from a .yaml file. In addition to modifying the plugin, I have added a fieldtype that lists all available Flickr photosets from a selected user and presents them in a selection dialog box in the Statamic control panel.

Having both a plug-in and a fieldtype in the same file will allow the easy one-click creation of photo and portfolio galleries from the Statamic control panel. 

## Possible use cases for this plugin

### Creation of photo gallery websites or portfolio websites

With the ability of applications like Adobe Lightroom to publish content directly to a Flickr photoset, it would allow a website owner to push one button inside the Adobe Lightroom application to publish an additional photo to a particular Flickr photoset and the website would be immediately updated without any additional work.

###Creation of photo blogs sections for travelers 

Flickr has mobile apps that allow the user to push photos to a particular photoset directly from a mobile phone or tablet. If this photoset is already referenced in a Statamic website, this would allow for instantaneous updates to a photo blog.

## Files included in this repo

1. flickr_sets folder containing:
    * ft.flickr_sets.php
    * pi.flickr_sets.php
2. flickr-sets.yaml
3. flickr_gallery.yaml
4. flickr.html

## Installation

As in many things Statamic, you will need to be comfortable moving these files into their proper place.

1. Place the entire flickr_sets folder containing the plug-in and fieldtype .php files into the _add-ons folder

2. Create another folder also named *flickr_sets* in the *_config/add-ons* folder, and drop the *flickr_sets.yaml* file into this folder. Edit this file and add your Flickr API key and Flickr UserID into the named fields. The user ID is the the funny looking one that shows up in a flickr photo as X787UFKN@08 or something similar. The values do not have to be in quotations.

3. Place the *flickr_gallery.yaml* file into the *_config/fieldsets folder*. This file can be edited if you want to add bells and whistles to what is displayed in the Statamic control panel when a new page is created and *Flickr Gallery* is chosen as the page type. 

4. Place the *flickr.html* into the *_themes/theme-name/templates* folder. Right now, this gallery creation step in the Control Panel automatically uses the flickr.html template to generate the html code that lists all the photos for a given photoset. 

The exact template called can be changed by editing or replacing the file referenced in  *flickr_sets.yaml* file. In the example, the template is hidden from the user and prevents someone from accidentally picking the wrong template to display the gallery.  You will probably want to modify this template to suit your needs. 

The example here merely lists all the photos from a given photoset with a thumbnail and image title in a html figure element. For testing purposes, I have also added an image to each image size available for the photos in a given photoset. You will probably want to delete all this extra stuff from the template after you decide what size photos you need.

Note that the call to the plugin uses a variable in single quotes named 'Photoset_ID' that uses the photoset identifier code that was set from the selection in the Control Panel. You can change this to a hard-coded reference to a specific photoset, but that sort of defeats the purpose of having a fieldtype in the first place.

## Usage

### Control Panel

When a new page is created, you will see a selection for 'Flickr Gallery' as a page type. Choose that option and you will see ![Page Creation](http://www.clayharmon.com/images/Statamic_CP.png) a page creation screen that shows a dropdown selection list that contains all the photosets for the Flickr user ID that has been set in the flickr_sets.yaml file. 

With the default template, any content entered into the content area appears *above* the gallery and can be used to embed a gallery meta-description if desired. 

The selection box will populate a YAML field named 'photoset_ID' in the front matter for the page.md file that is then used to pull down the list of images using the statamic custom tag structure.

### Using the tags within the template

Now that a gallery page has been created, the plugin is used to loop through and list all the available images for the previously selected photoset.

The tag structure to list all photos is:

```
      {{ flickr_sets:sets id= { photoset_ID }  limit="40"}}

      {{photo}}

        {{# Particulars for the photos go here for instance {{url_m}} will display the link to the medium sized image #}}

      {{/photo}}

      {{/flickr_sets:sets}}

```

Note that you do not have to do anything with the *photoset_ID* field. This value was set in the page YAML when it was created. All you need to do is specify which size image url or other information you want to list out for each of the photos retreived from the chosen photoset. 

### Tag parameters for {{flickr_sets:sets}} tag

* ```limits="X"``` - where 'X' is set to the number of photos to be returned - default is 5


### Tag outputs inside {{photo}} tags

* ```{{ownername}}``` - picture owner name
* ```{{title}}``` - picture title or caption
* ```{{url_sq}}``` - small square 75x75 px
* ```{{url_t}}``` - small 100px on longest side
* ```{{url_q}}``` - large square 150x150 px
* ```{{url_s}}``` - small 240px on longest side
* ```{{url_n}}``` - small 320 px on longest side
* ```{{url_m}}``` - medium 500px on longest side
* ```{{url_l}}``` - large 1000px on longest side
* ```{{url_o}}``` - original image size
* ```{{geo}}``` - supposedly lat-long location - I haven't tagged mine so I can't say if it works!

## Steal this book

Please take any of this code to create similar Statamic add-ons for photo-sharing services like Picasa, etc. You guys are a lot smarter than I am.
