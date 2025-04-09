# Mason
This module is used to implement various types of page blocks. 

## Configurable Preview
This is a Bootstrap 4 based card layout to preview resources. This was designed to be used as a catalogue preview that surfaces three pieces of information: A title, a sub-title or very brief description, and an image. The model use case was a book: Title, Author, Cover Art.

Configuration options are for a desktop view, and will be honored as much as possible for smaller viewports. However, the following restrictions apply:
For large viewports  (≥992px), the maximum columns will be set by the config, up to 3. Card orientation is as selected.
For medium viewports (≥768px), the maximum columns will be reduced to 2. Card orientation is as selected.
For small viewports (≥576px), the maximum columns will be reduced to 1. Card orientation is as selected.
for very small viewports (<576px), the card orientation will be vertical.

### Block configuration: 
**Resource type**: select which type of resource you would like to preview.

**Card Style**: There are two styles, vertical and horizontal. Horizontal features a card with the image on the left and the title and subtitle on the right. This uses the square image derivative that Omeka S prepares for all media, which allows cards for the same size. Vertical features an image on top with title and subtitle below. This uses the large image to span the with of the card, and as such cards in a row may be of different sizes based on the sizes of their images.

**Search query**: The query that will select which resources to display

**Limit**: The maximum number of resources you would like to display

**Maximum Columns**: The number of columns in a destop viewport 

**Preview title property**: Which property to use for the title. If the property you select has an Omeka resource as its value, then the title will be a link to that resrouce. 

**Preview subtitle property**: Which property to use for the sub-title. If the property you select has an Omeka resource as its value, then the title will be a link to that resrouce.   

### Styling
To sytle the title and subtitle, use the Custom CSS editor with the following selectors:
For the title use: `.configurablePreview-block .card-title` 
For the subtitle use: `.configurablePreview-block .card-text`

Example:
```
.configurablePreview-block .card-title {
  font-size: 2.2rem;
  font-style: italic;
}
```
