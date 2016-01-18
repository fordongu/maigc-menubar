<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Presentation\ActiveStateChecker;

class IlluminateItemRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'item';

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Item $item
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(Item $item,$prefix='sidebar')
    {

        $this->view=$prefix.'::'.$this->view;
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new IlluminateItemRenderer($this->factory))->render($child,$prefix);
            }

            $badges = [];
            foreach ($item->getBadges() as $badge) {
                $badges[] = (new IlluminateBadgeRenderer($this->factory))->render($badge,$prefix);
            }

            $appends = [];
            foreach ($item->getAppends() as $append) {
                $appends[] = (new IlluminateAppendRenderer($this->factory))->render($append,$prefix);
            }

            return $this->factory->make($this->view, [
                'item'    => $item,
                'items'   => $items,
                'badges'  => $badges,
                'appends' => $appends,
                'active'  => (new ActiveStateChecker())->isActive($item),
            ])->render();
        }
    }
}
