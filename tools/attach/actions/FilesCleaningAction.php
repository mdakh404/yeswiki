<?php

use YesWiki\Bazar\Service\EntryManager;
use YesWiki\Core\YesWikiAction;
use YesWiki\Core\Service\DbService;
use YesWiki\Core\Service\PageManager;

class FilesCleaningAction extends YesWikiAction
{
    protected $dbService;
    protected $entryManager;
    protected $pageManager;

    public function formatArguments($arg)
    {
        return([
            'media_delete' => (!empty($_POST['media_delete'])) ? $_POST['media_delete'] : null ,
        ]);
    }

    public function run()
    {
        if (!empty($aclMessage = $this->checkSecuredACL())) {
            return $aclMessage;
        }

        /* set services */
        $this->dbService = $this->getService(DbService::class);
        $this->entryManager = $this->getService(EntryManager::class);
        $this->pageManager = $this->getService(PageManager::class);

        if (!empty($this->arguments['media_delete'])) {
            // Check if the page received a post named 'media_delete'
            $file = $this->arguments['media_delete'];
            if (!unlink('files/'.$file)) {
                throw new Exception(_t('MEDIA_UNABLE_TO_DELETE_FILE'));
                return null;
            }
            $this->wiki->redirect($this->wiki->href());
        }

        // get data
        $filesToRecords = $this->getFilesToRecords();
        list($filesToTags, $emptyAttachTags) = $this->getFilesToTags();
        $latestTags = $this->getlatestTags();
        list($filesDisplay, $filesVersions) =
            $this->getFilesDisplay($filesToTags, $filesToRecords, $latestTags);

        $filesLastVersions = $this->decuplicateFilesVersions($filesVersions);
        $this->setMediaLatestVersion($filesDisplay);

        return $this->render('@attach/files-cleaning.twig', [
            'emptyAttachTags' => $emptyAttachTags,
            'filesDisplay' => $filesDisplay,
        ]);
    }

    /**
     * get filesToRecords
     * Build $filesToRecords
     * an array of media referenced by bazar records
     *    file => array(page, field name, field type)
     * @return array filesToRecords
     */
    private function getFilesToRecords(): ?array
    {
        $filesToRecords = [];
        $entries = $this->entryManager->search();
        if (!empty($entries)) {
            foreach ($entries as $tag => $entry) {
                foreach ($entry as $key => $value) {
                    if (preg_match('/^(?:data-)?(fichier|image)(.+)$/', $key, $matches)) {
                        // $value is the filename
                        // $matches[0] contains the entire search pattern
                        // $matches[1] contains the second subpattern: this is the field type
                        // $matches[2] contains the third subpattern: this is the field name
                        $filesToRecords[$value] = [$tag,$matches[2],$matches[1]];
                    }
                }
            }
        }
        return $filesToRecords;
    }
    /**
     * get filesToTags
     * Build $filesToTags
     *  an array of media referenced by {{attach}} actions in wiki pages
     *   file (without extension) => array(page, extension)
     * @return array [$filesToTags,$emptyAttachTags]
     */
    private function getFilesToTags(): ?array
    {
        $filesToTags = [];
        $emptyAttachTags = [];
        $pages = $this->pageManager->searchFullText('{{attach');
        if (!empty($pages)) {
            foreach ($pages as $page) {
                if (preg_match_all('/\{\{attach.*?file=\\\?"([^"]+)\\\?"[^\}]*\}\}/', $page['body'], $attaches)) {
                    foreach ($attaches[1] as $file) { // attaches['1'] contains all successive matches to parenthesized subpattern
                        if (($file != '') && ($file != ' ')) {
                            $fileName = explode(".", $file);
                            $filesToTags[$fileName[0]] = array('page' => $page['tag'], 'extension' => $fileName[1]);
                        } else {
                            $emptyAttachTags[] = $page['tag'];
                        }
                    }
                } else { // Strange! no matches in a record SQL found as matching!
                    throw new Exception(_t('MEDIA_ERROR_NO_MATCHES_IN_PAGES'));
                }
            }
        } else {
            throw new Exception(_t('MEDIA_UNABLE_TO_RETRIEVE_ATTACH_PAGES'));
        }
        return [$filesToTags,$emptyAttachTags];
    }

    /** get $latestTags
     * an array of wiki pages in their latest version
     * @return array $latestTags
     */
    private function getLatestTags(): ?array
    {
        $sql =    'SELECT tag FROM '.$this->dbService->prefixTable('pages')
                .' WHERE latest = "Y" AND tag IN ('.
                   'SELECT resource FROM '.$this->dbService->prefixTable('triples').
                   'WHERE (value != "fiche_bazar") AND (value != "liste")'.
                  ')';
        $latestTags = $this->dbService->loadAll($sql);
        if (empty($latestTags)) {
            throw new Exception(_t('MEDIA_UNABLE_TO_RETRIEVE_LATEST_PAGES'));
        }
        return $latestTags;
    }

    /*
        Build $filesDisplay
            an array of all files in the "files" directory with the required info to display
            file => (
                'bazar' => ,
                'media' => ,
                'page' => ,
                'extension => ,
                'time' => ,
                'pageIsActive' => ,
                'additionalPageText' => ,
                'pageIsLatest' => ,
                'latestPageVersionText' => ,
                'MediaIsLatest' => ,
                'latestMediaVersionText' => ,
                'deleteColText' => ,
            )
     */
    /**
     * @param array $filesToTags
     * @param array $filesToRecords
     * @param array $latestTags
     * @return array [$filesDisplay,$filesVersions]
     */
    private function getFilesDisplay(array $filesToTags, array $filesToRecords, array $latestTags): ?array
    {
        $remainingFilesToTags = $filesToTags;
        $filesDisplay = [];
        $filesVersions = []; // file => ('page' => , 'media' => , 'time' => , 'last' =>)
        foreach (glob('files/*.*') as $file) {
            $arr = explode("/", $file);
            $file = $arr[1];
            $RegExpOK = true;
            if (array_key_exists($file, $filesToRecords)) { // If the file is referenced by a bazar record
                $filesDisplay[$file]['bazar'] = true;
                $filesDisplay[$file]['media'] = $file;
                $filesDisplay[$file]['page'] = $filesToRecords[$file][0];
                $filesDisplay[$file]['additionalPageText'] = ' Champ = '.$filesToRecords[$file][1];
            // Beware, some parenthesized subpatterns are ungreedy (there is a question mark)
            } elseif (preg_match('`^([^_]+)_([^_]+)_\d{14}_(\d{14}).*\.(.*)`', $file, $match)) { // Two >s
                $this->setPagesInfilesDisplay($filesDisplay, $remainingFilesToTags, $file, $match[1], $match[2], $match[3], $match[4], $latestTags);
            } elseif (preg_match('`^([^_]+)_([^_]+)_(\d{14}).*\.(.*)`', $file, $match)) { // Only one >
                $this->setPagesInfilesDisplay($filesDisplay, $remainingFilesToTags, $file, $match[1], $match[2], $match[3], $match[4], $latestTags);
            } elseif (preg_match('`^(.*?)_vignette_(\d{3}_)\2(\d{14}).*\.(.*)`', $file, $match)) { // look for the first > (page time) => 2nd subpattern, and what's before => 1st subpattern
                // Both previous regexp were ungreedy
                // therefore, we are going to search $remainingFilesToTags for a corresponding media name using the $fileNameBits we have.
                $this->buildMediaAndPage($filesDisplay, $remainingFilesToTags, $file, $match[1], $match[3], $match[4], $latestTags);
            } elseif (preg_match('`^(.*?)_(\d{14}).*\.(.*)`', $file, $match)) { // Same as previous, without the vignette bit
                $this->buildMediaAndPage($filesDisplay, $remainingFilesToTags, $file, $match[1], $match[2], $match[3], $latestTags);
            } else { // Unable to find anything
                $this->setNotFound($filesDisplay, $file);
            }

            // For media attached to latest version of active pages, set $filesVersions
            if (!($filesDisplay[$file]['bazar']) && ! is_null($filesDisplay[$file]['media'])) {
                // We have a real page (no bazar record and no problem)
                if ($filesDisplay[$file]['pageIsActive'] && $filesDisplay[$file]['pageIsLatest']) {
                    // Media used on the latest version of an active page
                    $filesVersions[$file] = [
                        'page'	=> $filesDisplay[$file]['page'],
                        'media'	=> $filesDisplay[$file]['media'],
                        'time'	=> $filesDisplay[$file]['time'],
                        'last'	=> 'N'];
                }
            }
        } // End foreach(glob('files/*.*') as $file)
        return [$filesDisplay,$filesVersions];
    }

    /**
     * @param array &$filesDisplay
     * @param string $file
     */
    private function setNotFound(array &$filesDisplay, string $file)
    {
        $filesDisplay[$file]['bazar'] = false;
        $filesDisplay[$file]['media'] = null;	// _t('MEDIA_UNABLE_TO_UNDERSTAND_FILE_NAME')
    }

    /**
     * @param array &$filesDisplay
     * @param array &$remainingFilesToTags
     * @param string $file
     * @param string $page
     * @param null|string $media
     * @param null|string $time
     * @param null|string $extension
     * @param null|array $latestTags
     */
    private function setPagesInfilesDisplay(
        array &$filesDisplay,
        array &$remainingFilesToTags,
        string $file,
        string $page,
        ?string $media,
        ?string $time,
        ?string $extension,
        ?array $latestTags
    ) {
        $filesDisplay[$file]['bazar'] = false;
        $filesDisplay[$file]['media'] = $media;
        $filesDisplay[$file]['page'] = $page;
        if (($remainingFilesToTags[$media]['page'] ?? null) == $page) { // What the RegExp found is consistent with what was found in DB
            $filesDisplay[$file]['extension'] = $extension;
            $filesDisplay[$file]['time'] = $time; // In some cases, it's not upload time, but page version creation time
            $filesDisplay[$file]['pageIsActive'] = true;
            $filesDisplay[$file]['additionalPageText'] = '';
            $filesDisplay[$file]['pageIsLatest'] = true; // _t('MEDIA_USED_ON_PAGE_LATEST_VERSION')
            unset($remainingFilesToTags[$media]); // Suppress that file from the array
        } else { // Unconsistency between regexp and DB
            $filesDisplay[$file]['extension'] = '';
            $filesDisplay[$file]['time'] = '';
            if (array_search($page, $latestTags, true)) { // Page exists in its latest version
                $filesDisplay[$file]['pageIsActive'] = true;
            } else {
                $filesDisplay[$file]['pageIsActive'] = false;
            }
            $filesDisplay[$file]['additionalPageText'] = '';
            $filesDisplay[$file]['pageIsLatest'] = false; // _t('MEDIA_UNUSED_ON_PAGE_LATEST_VERSION')
        }
    }

    /**
     * @param array &$filesDisplay
     * @param array &$remainingFilesToTags
     * @param string $file
     * @param string $fileName
     * @param null|string $time
     * @param null|string $extension
     * @param null|array $latestTags
     */
    private function buildMediaAndPage(
        array &$filesDisplay,
        array &$remainingFilesToTags,
        string $file,
        string $fileName,
        ?string $time,
        ?string $extension,
        ?array $latestTags
    ) {
        $fileNameBits = explode("_", $fileName);
        $bitsNumber = count($fileNameBits);
        $i = $bitsNumber - 1;
        $trialMediaName = $fileNameBits[$i]; // build a media name form $fileNameBits (starting at the end)
        $media = '';
        $page = '';
        $found = false;
        do {
            if (array_key_exists($trialMediaName, $remainingFilesToTags)) { // there is a media with that name
                $remainingString = $fileNameBits[0]; // a page name form $fileNameBits (starting at the beginning)
                for ($j=1; $j < $i; $j++) {
                    $remainingString .= '_'.$fileNameBits[$j];
                }
                $media .= $trialMediaName."\n"; // Whatever happens on next test, this value is concatenated with preceding ones
                $page .= $remainingString."\n"; // Whatever happens on next test, this value is concatenated with preceding ones
                if ($remainingFilesToTags[$trialMediaName] == $remainingString) { // The page name for that media is correct (Perfect match)
                    $found = true;
                    $media = $trialMediaName; // Perfect match => replace the temp value
                    $page = $remainingString; // Perfect match => replace the temp value
                    unset($remainingFilesToTags[$trialMediaName]); // Suppress that file from the array
                    $i = 0; // Job finished, Let's get out
                }
            } // End of there is a media with that name
            $i--;
            $trialMediaName = $fileNameBits[$i].'_'.$trialMediaName;
        } while ($i > 0);
        // Here, we have three cases.
        // 1. We found a perfect match (media and page) and can set $filesDisplay
        // 2. We found at least one matching media name and we stored the last we found ($media) as weel as the corresponding, wrong, page ($page). We know it's not correct but that is information
        // 3. We found nothing that matches
        if ($found) { // Perfect match (media AND page)
            $this->setPagesInfilesDisplay($filesDisplay, $remainingFilesToTags, $file, $page, $media, $time, $extension, $latestTags);
        } elseif ($media == '') { // Neither perfect match, nor any consistent media name
            $this->setNotFound($filesDisplay, $file);
        } else { // Found some consistent media names and taking all of them along with the coresponding page names
            $filesDisplay[$file]['bazar'] = false;
            $filesDisplay[$file]['media'] = $media;
            $filesDisplay[$file]['page'] = $page;
            $filesDisplay[$file]['extension'] = $extension;
            $filesDisplay[$file]['time'] = $time;
            $filesDisplay[$file]['pageIsActive'] = false;
            $filesDisplay[$file]['additionalPageText'] = '';
            $filesDisplay[$file]['pageIsLatest'] = false;
        }
    }

    /**
     * Now, deduplicate (find the latest of numerus media files succesively called by the same page)
     * @param array &$filesVersions
     * @return array $filesLastVersions
     */
    private function decuplicateFilesVersions(array &$filesVersions): ?array
    {
        $filesLastVersions = array(); // $page.'0µ0'.$mediaName => $time
        if (array_key_exists(0, $filesVersions)) {
            unset($filesVersions[0]);
        }
        foreach ($filesVersions as $file => $fileVersion) {
            $compoundKey = $fileVersion['page'].'0µ0'.$fileVersion['media'];
            if (array_key_exists($compoundKey, $filesLastVersions)) { // Found a record for (page, media) key
                if ($filesLastVersions[$compoundKey] < $fileVersion['time']) {
                    $filesLastVersions[$compoundKey] = $fileVersion['time'];
                }
            } else { // create a record for the (page, media) pair
                $filesLastVersions[$compoundKey] = $fileVersion['time'];
            }
        } // End of foreach ($filesVersions as $file => $fileVersion)
        return $filesLastVersions;
    }

    /**
     * Set the media latest version
     * @param array &$filesDisplay
     */
    private function setMediaLatestVersion(array &$filesDisplay)
    {
        if (array_key_exists(0, $filesDisplay)) {
            unset($filesDisplay[0]);
        }
        foreach ($filesDisplay as $file => $fileDisplay) {
            $fileDisplay['latestMediaVersion'] = false;
            $latestMedia = false;
        
            // For media attached to latest version of active pages, set $filesVersions
            if (!($filesDisplay[$file]['bazar']) && ! is_null($filesDisplay[$file]['media'])) {
                // We have a real page (no bazar record and no problem)
                if ($filesDisplay[$file]['pageIsActive'] && $filesDisplay[$file]['pageIsLatest']) {
                    // Media used on the latest version of an active page
                    $fileDisplay['latestMediaVersion'] = true; // _t('MEDIA_LATEST_VERSION');
                }
            }
        } // End foreach ($filesDisplay as $file => $fileDisplay)
    }
}
