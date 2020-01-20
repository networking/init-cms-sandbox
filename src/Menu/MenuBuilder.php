<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Networking\InitCmsBundle\Model\MenuItem;
use Networking\InitCmsBundle\Menu\MenuBuilder as BaseMenuBuilder;
use Knp\Menu\MenuItem as KnpMenuItem;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder extends BaseMenuBuilder
{

    /**
     * @param $menuName
     * @param bool $rootClass
     * @param int $startDepth
     * @param bool $showOnlyCurrentChildren
     * @return ItemInterface
     */
    public function buildMenu($menuName, $rootClass = false, $startDepth = 1, $showOnlyCurrentChildren = false)
    {
        $menu = $this->factory->createItem('main');

        if($rootClass){
            $menu->setChildrenAttribute('class', $rootClass);
        }

        $menuIterator = $this->getFullMenu($menuName);
        if (!$menuIterator) {
            return $menu;
        }
        $menu = $this->createMenu($menu, $menuIterator, $startDepth);
        if($showOnlyCurrentChildren){
            $this->showOnlyCurrentChildren($menu);
        }
        $this->setRecursiveAttribute($menu, ['class' => 'nav nav-list']);

        return $menu;
    }



    /**
     * Creates the login and change language navigation for the right side of the top frontend navigation.
     *
     * @param RequestStack $requestStack
     * @param $languages
     * @param string $classes
     * @param bool $dropDownMenu
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createLangMenu(
        RequestStack $requestStack,
        $languages,
        $classes = 'nav pull-right'
    ) {
        $menu = $this->factory->createItem('root');
        if ($classes) {
            $menu->setChildrenAttribute('class', $classes);
        }

        foreach ($languages as $language) {
            $route = $this->router->generate('networking_init_change_language',['oldLocale' => $this->request->getLocale(), 'locale' => $language['locale']]);
            $node = $menu->addChild( $language['label'],['uri' => $route]);
            if ($language['locale'] == $requestStack->getCurrentRequest()->getLocale()) {
                $node->setCurrent(true);
            }
            $node->setLinkAttribute('class', 'nav-link');
            $node->setExtra('translation_domain', false);
        }

        return $menu;
    }

    /**
     * @param ItemInterface $item
     * @param MenuItem $node
     * @param $startDepth
     * @return bool|ItemInterface
     */
    public function addNodeToMenu(ItemInterface $item, MenuItem $node, $startDepth)
    {
        if ($node->getLvl() < $startDepth) {
            return false;
        }

        if ($node->getLvl() > $startDepth) {
            $item = $this->getParentMenu($item, $node);
        }

        if (is_object($item)) {
            $knpMenuNode = $this->createFromMenuItem($node);
            if (!is_null($knpMenuNode)) {
                $item->addChild($knpMenuNode);
                $knpMenuNode->setLinkAttribute('class', $node->getLinkClass().' nav-link');
                $knpMenuNode->setExtra('translation_domain', false);
                if ($node->getVisibility() != MenuItem::VISIBILITY_PUBLIC && !$this->isLoggedIn) {
                    $knpMenuNode->setDisplay(false);
                }

                return $knpMenuNode;
            }
        }

        return false;
    }
}