<?php


namespace Mason\Site\BlockLayout;

use Doctrine\ORM\EntityManager;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Omeka\Module\Manager;
use Laminas\View\Renderer\PhpRenderer;

class DisableSubNav extends AbstractBlockLayout
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Manager
     */
    protected $moduleManager;

    public function __construct(EntityManager $entityManager, Manager $moduleManager)
    {
        $this->entityManager = $entityManager;
        $this->moduleManager = $moduleManager;
    }


    public function getLabel()
    {
        return 'Disable Sub-Menu Navigation'; // @translate
    }

//    public function getPreview($page_id, $default, $size, PhpRenderer $view)
//    {
//        //default thumbnail if the page has no media
//        $img = $default;
//        $alt = 'Exhibit landing page';
//
//        $page = $view->api()->read('site_pages', ['id' => $page_id])->getContent();
//
//        //get the first media attachment on the target page
//        foreach ($page->blocks() as $block) {
//            if (get_class($block) === 'Omeka\Api\Representation\SitePageBlockRepresentation') {
//                if ($block->attachments()) {
//                    $media = false;
//                    foreach ($block->attachments() as $attachment):
//                        if ($attachment->media()) {
//                            $media = $attachment->media();
//                        } elseif ($attachment->item()->primaryMedia()) {
//                            $media = $attachment->item()->primaryMedia();
//                        }
//                        if ($media) {
//                            if ($thumbnail = $media->thumbnail()) {
//                                $img = $thumbnail->assetUrl();
//                            } else {
//                                $img = $media->thumbnailUrl($size);
//                            }
//                            if (array_key_exists('o-module-alt-text:alt-text', $media->primaryMedia()->jsonSerialize())
//                                && $media->primaryMedia()->jsonSerialize()['o-module-alt-text:alt-text']
//                            ) {
//                                $alt = $media->primaryMedia()->jsonSerialize()['o-module-alt-text:alt-text'];
//                            } else {
//                                $alt = 'Thumbnail preview for next page';
//                            }
//                            break 2;
//                        }
//                    endforeach;
//                }
//            }
//        }
//
//        $title = $page->title();
//        $preview['img_src'] = $img;
//        $preview['alt'] = $alt;
//        $preview['title'] = $title;
//        $preview['url'] = $page->slug();
//        $preview['site_page'] = $page;
//
//        return $preview;
//    }

//    public function getChildPages($block, PhpRenderer $view)
//    {
//
//        //TODO: first check to to see if the theme has set a default image
//        $default_img = $view->assetUrl('img/Default.png', 'Mason');
//
//        $indents = [];
//        $iterate = function ($linksIn, $depth = 0) use (&$iterate, &$indents) {
//            foreach ($linksIn as $key => $data) {
//
//                //if we it is a page, then the depth is 0
//                if ('page' === $data['type']) {
//                    $indents[$data['data']['id']] = $depth;
//                }
//                if (isset($data['links'])) {
//                    $iterate($data['links'], $depth + 1);
//                }
//            }
//        };
//
//        //the docstring is wrong so the autosuggest thinks page() is a SiteRepresentation
//        $site = $block->page()->site();
//
//        $iterate($site->navigation());
//
//        $exhibits_depth = $block->dataValue('child_pages');
//
//
//        $exhibits = [];
//
//
//        //filter array for values that match given depth
//
//        /*
//         * This is a pretty dumb way to do it, but for now it is what works. indents is the function used to get the
//         * navigation hierarchy and it is stored in an "ordered" associative array of page_id => depth.
//         */
//        $current_page_id = $block->page()->id();
//        $edge = false;
//        $upper_depth = 0;
//        foreach ($indents as $page_id => $depth):
//            if ($depth <= $upper_depth) {
//                $edge = false;
//            }
//            //if the page was deleted it can still remain in nav. Catch that and skip if so.
//            try {
//                $view->api()->read('site_pages', ['id' => $page_id]);
//            } catch (\Exception $exception) {
//                continue;
//            }
//            if ($edge === true && $depth<=$upper_depth+$exhibits_depth) {
//                $exhibits[$page_id] = $this->getPreview($page_id, $default_img, 'large', $view);
//            }
//            if ($page_id === $current_page_id) {
//                $edge = true;
//                $upper_depth = $depth;
//            }
//
//
//        endforeach;
//
//        return $exhibits;
//    }

    public function form(
        PhpRenderer $view,
        SiteRepresentation $site,
        SitePageRepresentation $page = null,
        SitePageBlockRepresentation $block = null
    ) {

        $form = new Form();

        return $view->formCollection($form, false);
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        $view->pageViewModel->setVariable('displayNavigation', false);
    }
}
