# User Interface Documentation

# High Level Design

All UI is shown on `index.php`.
User actions are almost all done through ajax calls.
The sole exception to this is file loading, which is still mostly ajax, but uses an iframe to upload the user selected file.

## index.php
`index.php` has the following features:

* 4 vertical content panes.
    * Each is at a minimum 20em wide (Total of 80em or ~1280px)
    > Note that buttons **can** currently overlap text if the text is too long.
    * 1px seperation between each pane
    * #primary is on the far left, with #secondary to the right of it, #tertiary to the right of #secondary, and #quaternary to the right of #tertiary
* A #messages alert box
    * Centered at the top of the screen
    * 25px high
    * At least 300px wide
    * Displays over almost everything (z-index: 100)

* A #menu panel containing 7 buttons
    * Absolute positioned:
        * Bottom, Right of screen
    * 100px wide
    * 400px heigh
    * Each button:
        * 100px wide
        * 55px heigh
        * Text is left aligned
        * Oval shaped

* A stats panel
    * Absolute positioned:
        * Top, Right of screen
    * 120px wide
    * 200px heigh
* Popup windows covering most of the page
* Tooltips when a user hovers over a '?'

Final minimal page dimensions to display everything are 1400 X 630.
