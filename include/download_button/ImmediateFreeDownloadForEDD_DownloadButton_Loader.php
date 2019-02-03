<?php
/**
 * Immediate Free Download for Easy Digital Downloads
 *
 * [PROGRAM_URI]
 * Copyright (c) <COPYRIGHT_YEARS> Michael Uno
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
        new ImmediateFreeDownloadForEDD_Event_AllowDownload;
    }

}