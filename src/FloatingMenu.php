<?php
/**
 * Email: srdjandrakul@gmail.com
 * Date: 1/17/2019
 * Time: 11:40 PM
 */

namespace srdjan\floatingmenu;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class FloatingMenu extends Widget
{
    const STATE_COLLAPSED = 'collapsed';
    const STATE_EXPANDED = 'expanded';

    const COOKIE_KEY = 'fmwidget-state';

    public $items = [];

    public $options = [];
    public $itemOptions = [];
    public $containerOptions = [];
    public $toggleOptions = [];

    public $encodeLabels = false;

    public $rememberState = true;

    public $firstItemCssClass;

    public $activeCssClass = 'active';

    public $linkTemplate = '<a href="{url}">{label}</a>';

    public $labelTemplate = '{label}';

    public $route;

    public $params;

    public function init()
    {
        parent::init();

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }

        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    public function run()
    {
        $this->registerAssets();

        $items = $this->normalizeItems($this->items);

        if (!empty($items)) {
            return $this->renderWidget($items);
        }
    }

    public function getMenuState()
    {
        if(!$this->rememberState) {
            return self::STATE_EXPANDED;
        }

        if(isset($_COOKIE[FloatingMenu::COOKIE_KEY]) && !empty($_COOKIE[FloatingMenu::COOKIE_KEY]) && $this->getIsMenuStateValid()) {
            return $_COOKIE[FloatingMenu::COOKIE_KEY];
        }

        return self::STATE_EXPANDED;
    }

    public function getIsMenuStateValid()
    {
        return in_array($_COOKIE[FloatingMenu::COOKIE_KEY], $this->getAvailableMenuStates());
    }

    public function getAvailableMenuStates()
    {
        return [
            self::STATE_EXPANDED,
            self::STATE_COLLAPSED
        ];
    }

    public function renderWidget($items)
    {
        $containerOptions = $this->containerOptions;
        $containerTag = ArrayHelper::remove($containerOptions, 'tag', 'div');

        $state = $this->getMenuState();

        Html::addCssClass($containerOptions, ['fmwidget', "fmwidget-{$state}"]);

        $containerOptions['data-state'] = $state;

        $html = Html::beginTag($containerTag, $containerOptions);

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        Html::addCssClass($options, 'fmwidget-nav');

        $html .= Html::tag($tag, $this->renderItems($items), $options);
        $html .= Html::endTag('div');
        
        return $html;
    }

    public function renderItems($items)
    {
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = ['fmwidget-item'];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            Html::addCssClass($options, $class);
            Html::addCssStyle($options, "background-color: {$this->getColorAtIndex($i)};", false);

            $menu = $this->renderItem($item);
            $lines[] = Html::tag($tag, $menu, $options);
        }

        $lines[] = $this->renderToggle();

        return implode("\n", $lines);
    }

    public function renderToggle()
    {
        $toggleOptions = $this->toggleOptions;
        Html::addCssClass($toggleOptions, "fmwidget-toggle");

        $collapseOptions = ArrayHelper::remove($toggleOptions, 'collapseOptions', []);
        $expandOptions = ArrayHelper::remove($toggleOptions, 'expandOptions', []);

        $isCollapsed = $this->getMenuState() === self::STATE_COLLAPSED;

        Html::addCssClass($collapseOptions, 'fmwidget-collapse');
        Html::addCssClass($expandOptions, 'fmwidget-expand');

        Html::addCssStyle($collapseOptions, $isCollapsed ? "display: none;" : "");
        Html::addCssStyle($expandOptions, $isCollapsed ? "" : "display: none;");

        return Html::tag('li',
            Html::tag('i', '', $collapseOptions) . Html::tag('i', '', $expandOptions),
            $toggleOptions
        );
    }

    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label']
        ]);
    }

    public function normalizeItems($items)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }

            if (!isset($item['label'])) {
                $item['label'] = '';
            }

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            if (!isset($item['active'])) {
                $items[$i]['active'] = $this->isItemActive($item);
            } elseif ($item['active'] instanceof \Closure) {
                $items[$i]['active'] = call_user_func($item['active'], $item, $this->isItemActive($item), $this);
            }
        }

        return array_values($items);
    }

    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = Yii::getAlias($item['url'][0]);
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    protected function registerAssets()
    {
        $view = $this->getView();
        FloatingMenuAsset::register($view);

        $view->registerJs("fmwidget.init();");
    }

    public function getColorAtIndex($index)
    {
        $colors = [
            '#3B5998',
            '#55acee',
            '#25d366',
            '#95D03A',
            '#8a3ab9',
            '#505e7c'
        ];

        return $colors[$index % 6];
    }
}