<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Maatwebsite\Sidebar\Sidebar;

class IlluminateSidebarRenderer implements SidebarRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'menu';

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Sidebar $sidebar
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(Sidebar $sidebar,$prefix='sidebar')
    {
        $menu = $sidebar->getMenu();

        $this->view=$prefix.'::'.$this->view;
        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new IlluminateGroupRenderer($this->factory))->render($group,$prefix);
            }

            return $this->factory->make($this->view, [
                'groups' => $groups
            ]);
        }
    }
}
