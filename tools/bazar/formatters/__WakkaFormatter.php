<?php

namespace YesWiki\Bazar;

use YesWiki\Bazar\Controller\EntryController;
use YesWiki\Bazar\Service\EntryManager;
use YesWiki\Core\YesWikiFormatter;

class __WakkaFormatter extends YesWikiFormatter
{
    public function formatArguments($args)
    {
        $entryManager = $this->getService(EntryManager::class);
        $tag = $this->wiki->GetPageTag();
        if ($entryManager->isEntry($tag) && isset($args['text']) && $args['text'] === $this->wiki->page['body']) {
            $entryController = $this->getService(EntryController::class);
            return ['text' => '""'.$entryController->view($tag, 0).'""'];
        }
        return [];
    }

    public function run()
    {
    }
}
