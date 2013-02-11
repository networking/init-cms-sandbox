<?php
/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sandbox\InitCmsBundle\Component\Menu;

use Networking\InitCmsBundle\Component\Menu\NavbarMenuBuilder,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\DependencyInjection\Container,
    Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder,
    Knp\Menu\FactoryInterface,
    Knp\Menu\Iterator\RecursiveItemIterator,
    Networking\InitCmsBundle\Entity\MenuItem as Menu,
    Networking\InitCmsBundle\Entity\MenuItemRepository,
    Networking\InitCmsBundle\Component\Menu\MenuSubItemFilterIterator,
    Networking\InitCmsBundle\Entity\Page;

class NavbarFrontendMenuBuilder extends NavbarMenuBuilder
{
    /**
     * @param \Knp\Menu\FactoryInterface                                $factory
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     * @param \Symfony\Component\DependencyInjection\Container          $serviceContainer
     */
    public function __construct(
        FactoryInterface $factory,
        SecurityContextInterface $securityContext,
        Container $serviceContainer
    ) {
        parent::__construct($factory, $securityContext, $serviceContainer);
    }

    public function createFooterMenu(Request $request, $path, $classes='')
    {
        $repository = $this->serviceContainer->get('doctrine')->getRepository('NetworkingInitCmsBundle:MenuItem');
        $menu = $this->createNavbarMenuItem();
        $menu->setChildrenAttribute('class', $classes);
        $menu->setCurrentUri($request->getRequestUri());
        $mainMenu = $repository->findOneBy(
            array('name' => $path, 'locale' => $this->serviceContainer->get('request')->getLocale())
        );
        if (!$mainMenu) {
            return $menu;
        }

        $menuIterator = new \RecursiveIteratorIterator(
            new RecursiveItemIterator($mainMenu->getChildrenByStatus($this->viewStatus)),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($menuIterator as $childNode) {
            if ($menuIterator->getDepth() > 0) {
                $parentMenu = $this->getMenuParentItem($menu, $childNode, $menuIterator->getDepth());
                $parentMenu->addChild($this->createFromNode($childNode));
            } else {
                $menu->addChild($this->createFromNode($childNode));
            }
        }
        return $menu;
    }

    /**
     * Creates the main page navigation for the left side of the top frontend navigation
     * @param Request $request
     * @param $path
     * @param string $classes
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(Request $request, $path, $classes= '')
    {

        /** @var $repository MenuItemRepository */
        $repository = $this->serviceContainer->get('doctrine')
            ->getRepository('NetworkingInitCmsBundle:MenuItem');
        $menu = $this->createNavbarMenuItem();
        $menu->setChildrenAttribute('class', $classes);
        $menu->setCurrentUri($request->getRequestUri());

        /** @var $mainMenu Menu */
        $mainMenu = $repository->findOneBy(
            array('name' => $path, 'locale' => $this->serviceContainer->get('request')->getLocale())
        );

        if (!$mainMenu) {
            return $menu;
        }

        $menuIterator = new \RecursiveIteratorIterator(
            new RecursiveItemIterator($mainMenu->getChildrenByStatus($this->viewStatus)),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($menuIterator as $childNode) {
            if ($menuIterator->getDepth() > 0) {
                $parentMenu = $this->getMenuParentItem($menu, $childNode, $menuIterator->getDepth());
                $parentMenu->addChild($this->createFromNode($childNode));
            } else {
                $menu->addChild($this->createFromNode($childNode));
            }
        }

        return $menu;
    }


    /**
     * Used to create nodes for the language navigation in the front- and backend
     *
     * @param Knp\Menu\MenuItem $menu
     * @param array $languages
     * @param $currentLanguage
     * @param string $route
     */
    public function createNavbarsLangMenu(
        &$menu,
        array $languages,
        $currentLanguage,
        $route = 'networking_init_change_language'
    ) {
      foreach($languages as $language)
        {
            $node = $menu->addChild(
                $language['label'],
                array('uri' => $this->router->generate($route, array('locale' => $language['locale'])))
            );
            if($language['locale'] == $currentLanguage){
                $node->setCurrent(true);
            }
        }
    }
}