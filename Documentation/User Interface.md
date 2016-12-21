#User Interface Documentation

#High Level Design

All UI is shown on `index.php`.
User actions are almost all done through ajax calls.
The sole exception to this is file loading, which is still mostly ajax, but uses an iframe to upload the user selected file.

##index.php
`index.php` has the following features:

* 4 vertical content panes.
    * Each is at a minimum 20em wide (Total of 80em or ~1280px)
    > Note that buttons **can** currently overlap text if the text is too long.
    * 1px seperation between each pane
    * #primary is on the far left, with #secondary to the right of it, #tertiary to the right of #secondary, and #quaternary to the right of #tertiary
    * Displays over some things (z-index:2)
* A #messages alert box
    * Centered at the top of the screen
    * 25px high
    * At least 300px wide
    * Displays over almost everything (z-index: 100)
* 7 buttons on the bottom right of the page (Each is identical except for position)
    * 55px heigh
    * 100px wide
    * Padding (Currently not actually used)
        * 10px top
        * 25px right
    * 40px multicolor border????? (according to css, but strange things are afoot here)
    * Absolute positioned:
        * 0px right
        * 10,70,130, etc.. px from bottom (60px increments)
        * All buttons together take up ~430px
    * Text is left aligned
* A stats panel on the top right of the page
    * Absolute position top, right 0
    * 120px wide
    * 200px heigh
* Popup windows covering most of the page
* Tooltips when a user hovers over a '?'

Final minimal page dimensions to display everything are 1400 X 630.
