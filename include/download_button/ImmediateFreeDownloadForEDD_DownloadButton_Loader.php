<?php
/**
 * Immediate Free Download for Easy Digital Downloads
 *
 * https://github.com/michaeluno/immediate-free-download-for-easy-digital-downloads
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Loads the download button component.
 *
 * @since       0.0.1
 */
class ImmediateFreeDownloadForEDD_DownloadButton_Loader {

    public function __construct() {
        new ImmediateFreeDownloadForEDD_DownloadButton;
        new ImmediateFreeDownloadForEDD_Event_IssueDownloadURL;
    }

}